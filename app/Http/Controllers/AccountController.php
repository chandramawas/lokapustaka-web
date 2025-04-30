<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentSubscription = $user->subscriptions()
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->first();
        return view('account.index', compact('user', 'currentSubscription'));
    }

    public function showSettings()
    {
        $user = Auth::user();
        return view('account.settings', compact('user'));
    }

    //Handle Account Data Update
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Laki-Laki,Perempuan,Lainnya',
            'birthdate' => 'required|date|date_format:Y-m-d',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'gender.in' => 'Jenis kelamin yang dipilih tidak valid.',
            'birthdate.required' => 'Tanggal lahir tidak boleh kosong.',
            'birthdate.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'birthdate.date_format' => 'Format tanggal lahir tidak sesuai. Gunakan format YYYY-MM-DD.',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->birthdate = $request->birthdate;
        $user->save();

        return redirect()->route('account.settings')->with('update-success', 'Profil berhasil diperbarui!');
    }

    public function showChangePassword()
    {
        return view('account.change-password');
    }

    //Handle Password Update
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Password lama tidak boleh kosong.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.required' => 'Password baru tidak boleh kosong.',
            'password.min' => 'Password baru harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi Password tidak boleh kosong.',
        ]);

        //Update Password
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.change-password')->with('update-success', 'Password berhasil diperbarui!');
    }

    public function showSubscription()
    {
        $subscriptions = auth()->user()->subscriptions()->orderBy('start_date', 'desc')->get();
        $currentSubscription = auth()->user()->activeSubscription();

        return view('account.subscription-info', compact('subscriptions', 'currentSubscription'));
    }

    public function showPayment()
    {
        $payments = auth()->user()->payments()->orderBy('created_at', 'desc')->get();

        return view('account.payment-history', compact('payments'));
    }
}
