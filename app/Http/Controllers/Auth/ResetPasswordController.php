<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        //Validasi form reset password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required',
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
            'token.required' => 'Token tidak valid.',
        ]);

        //Proses reset password
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                //Setelah berhasil, login otomatis
                Auth::login($user);
            }
        );

        if ($response == Password::PASSWORD_RESET) {
            return redirect()->route('home')->with('status', 'Password berhasil diperbarui!');
        }

        return back()->withErrors(['email' => [trans($response)]]);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
}
