<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Subscription;
use App\Models\Payment;
use Log;
use Midtrans\Snap;
use Str;

class SubscriptionController extends Controller
{
    public function index()
    {
        $activeSubscription = auth()->user()->activeSubscription();
        return view('subscription.index', compact('activeSubscription'));
    }

    public function checkout($type)
    {
        // Validasi Jenis Paket
        if (!in_array($type, ['bulanan', 'tahunan'])) {
            abort(404);
        }

        $user = auth()->user();
        $activeSubscription = $user->activeSubscription();

        $plans = config('subscription.plans');

        $selectedPlan = $plans[$type];

        return view('subscription.checkout', compact('selectedPlan', 'type', 'activeSubscription'));
    }

    public function pay(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $user = auth()->user();
        $plans = config('subscription.plans');

        $type = $request->input('type');
        if (!isset($plans[$type])) {
            return back()->with('error', 'Tipe langganan tidak valid.');
        }

        $plan = $plans[$type];
        $planId = $plan['id'];
        $price = $plan['price'];
        $year = now()->format('Y');
        $month = now()->format('m');

        $orderId = "{$year}{$month}-{$user->id}-{$planId}-" . strtoupper(Str::random(6));

        Payment::create(
            [
                'order_id' => $orderId,
                'user_id' => $user->id,
                'amount' => $price,
                'status' => 'pending',
                'method' => 'unknown',
            ],
        );

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            "item_details" => [
                [
                    "id" => $planId,
                    "price" => $price,
                    "quantity" => 1,
                    "name" => $plan['name'],
                ],
            ],
        ];

        $snapUrl = Snap::createTransaction($payload)->redirect_url;
        return redirect($snapUrl);
    }

    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        $orderData = Payment::where('order_id', $orderId)->with('subscription')->first();
        if (!$orderData) {
            return redirect()->route('subscription.index')->with('error', 'Order ID tidak Valid.');
        }

        return view('subscription.finish', compact('orderData'));
    }

    public function midtransCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
        }

        $parts = explode('-', $request->order_id);
        if (count($parts) < 4) {
            return response()->json(['status' => 'error', 'message' => 'Order ID tidak valid'], 400);
        }

        $userId = intval($parts[1]);
        $planId = intval($parts[2]);

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan'], 404);
        }

        $plans = collect(config('subscription.plans'));
        $plan = $plans->firstWhere('id', $planId);

        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan ID tidak valid'], 400);
        }

        $type = $plans->search($plan); // dapetin key-nya: bulanan / tahunan
        $price = $request->gross_amount;
        $duration = $plan['duration'];

        $status = match ($request->transaction_status) {
            'settlement' => 'completed',
            default => 'failed',
        };

        if ($status === 'completed') {
            DB::beginTransaction();
            try {
                $active = $user->activeSubscription();
                if ($active && $active->type === $type && $active->end_date > now()) {
                    $startDate = $active->start_date;
                    $endDate = Carbon::parse($active->end_date)->addDays($duration);
                } elseif ($active) {
                    $active->update(['is_active' => false]);
                    $startDate = now();
                    $endDate = now()->addDays($duration);
                } else {
                    $startDate = now();
                    $endDate = now()->addDays($duration);
                }

                $subscription = Subscription::updateOrCreate(
                    ['user_id' => $user->id, 'type' => $type, 'is_active' => true],
                    [
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ],
                );

                Payment::updateOrCreate(
                    ['order_id' => $request->order_id, 'user_id' => $user->id],
                    [
                        'subscription_id' => $subscription->id,
                        'amount' => $price,
                        'status' => $status,
                        'method' => $request->payment_type,
                        'paid_at' => now(),
                    ],
                );

                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Pembayaran Berhasil!'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Terjadi Kesalahan'], 500);
            }
        } else {
            try {
                DB::beginTransaction();
                Payment::updateOrCreate(
                    ['order_id' => $request->order_id, 'user_id' => $user->id],
                    [
                        'amount' => $price,
                        'status' => $status,
                        'method' => $request->payment_type,
                    ],
                );
                DB::commit();
                return response()->json(['message' => 'Status bukan settlement'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => 'Terjadi Kesalahan'], 500);
            }
        }
    }
}
