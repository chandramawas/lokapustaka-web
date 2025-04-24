<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        //Validasi email
        $request->validate(
            ['email' => 'required|email|exists:users,email'],
            [
                'email.required' => 'Email tidak boleh kosong.',
                'email.email' => 'Format email tidak valid.',
                'email.exists' => 'Email tidak terdaftar.',
            ],
        );

        //Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email'),
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('reset-link-sent', 'Link reset password sudah dikirim ke email anda!')
            : back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
}
