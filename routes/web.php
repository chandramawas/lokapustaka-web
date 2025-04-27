<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome Page
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Email Verification
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
    ->middleware('auth')
    ->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Reset Password Routes
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

// Account Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/account', [AccountController::class, 'index'])
        ->name('account.index');
    Route::get('/account/settings', [AccountController::class, 'showSettings'])
        ->name('account.settings');
    Route::post('/account/settings', [AccountController::class, 'update'])
        ->name('account.update');
    Route::get('/account/change-password', [AccountController::class, 'showChangePassword'])
        ->name('account.change-password');
    Route::post('/account/change-password', [AccountController::class, 'updatePassword'])
        ->name('account.update-password');
    Route::get('/account/subscription', [AccountController::class, 'showSubscription'])
        ->name('account.subscription');
});

//Homepage
Route::get('/', function () {
    return view('home');
})->name('home')->middleware(['auth', 'verified']);