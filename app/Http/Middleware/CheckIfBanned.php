<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBanned
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_banned) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah dibanned.',
            ]);
        }

        return $next($request);
    }
}
