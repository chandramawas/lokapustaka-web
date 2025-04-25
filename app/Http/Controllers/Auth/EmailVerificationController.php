<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    // Menampilkan halaman verifikasi email
    public function notice()
    {
        return view('auth.verify-email');
    }

    // Menangani link verifikasi email
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('home');
    }

    // Mengirim ulang link verifikasi email
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('new-verif-link', 'Link verifikasi sudah dikirim ulang!');
    }
}
