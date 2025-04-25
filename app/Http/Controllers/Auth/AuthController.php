<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
            'name' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            //Error Message
            'email.unique' => 'Email sudah terdaftar.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'name.required' => 'Nama tidak boleh kosong.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong.',
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Kalau user tidak ditemukan
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        // Kalau password salah
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'password' => 'Password salah.',
            ]);
        }

        // Login sukses
        return redirect()->route('home');
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
