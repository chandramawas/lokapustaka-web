<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Subscription;
use App\Models\Payment;

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
        $user = auth()->user();

        $plans = config('subscription.plans');

        $type = $request->input('type');
        $price = $plans[$type]['price'];
        $duration = $plans[$type]['duration'];

        DB::beginTransaction();

        try {
            $active = $user->activeSubscription();

            if ($active && $active->type === $type && $active->end_date > now()) {
                //lanjutkan langganan
                $startDate = $active->start_date;
                $endDate = Carbon::parse($active->end_date)->addDays($duration);
            } elseif ($active) {
                //reset langganan atau mulai lagi (setelah inactive)
                $active->update(['is_active' => false]);
                $startDate = now();
                $endDate = now()->addDays($duration);
            } else {
                //mulai baru
                $startDate = now();
                $endDate = now()->addDays($duration);
            }

            // update atau buat langganan baru
            $subscription = Subscription::updateOrCreate(
                ['user_id' => $user->id, 'type' => $type, 'is_active' => true],
                [
                    'user_id' => $user->id,
                    'type' => $type,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'is_active' => true,
                ],
            );

            //simpan riwayat pembayaran
            Payment::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'amount' => $price,
                'status' => 'completed',
                'method' => 'manual',
                'paid_at' => now(),
            ]);

            DB::commit();

            return back()->with(['status' => 'success', 'status_title' => 'Pembayaran Berhasil!', 'status_message' => 'Terima kasih telah bergabung dengan layanan premium kami. Nikmati akses premium hingga ' . Carbon::parse($endDate)->translatedFormat('d F Y') . '.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['status' => 'failed', 'status_title' => 'Pembayaran Gagal!', 'status_message' => 'Terjadi kesalahan saat memproses pembayaran.']);
        }
    }
}
