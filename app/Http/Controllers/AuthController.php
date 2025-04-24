<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //Show Register Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle Register
    public function register(Request $request)
    {
        //Validasi data dari form
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            //Error Message
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        //Create user baru
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        //Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        //Redirect ke halaman setelah berhasil login
        return redirect()->route('verification.notice');
    }

    //Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        //Validasi data dari form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        //Cek kredensial
        if (Auth::attempt($request->only('email', 'password'))) {
            //Redirect ke halaman setelah berhasil login
            return redirect()->route('home');
        }

        //Jika gagal, kembali ke halaman login dengan pesan error
        throw ValidationException::withMessages([
            'password' => 'Email atau password salah.',
        ]);
    }
}
