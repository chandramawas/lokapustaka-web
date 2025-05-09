<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BookshelfController;
use App\Http\Controllers\ReadBookController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
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

// Route APP User
Route::middleware(['auth', 'verified'])->group(function () {
    // Account Routes
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
    Route::get('/account/subscription-info', [AccountController::class, 'showSubscription'])
        ->name('account.subscription-info');
    Route::get('/account/payment-history', [AccountController::class, 'showPayment'])
        ->name('account.payment-history');

    // Subscription Routes
    Route::get('/subscription', [SubscriptionController::class, 'index'])
        ->name('subscription.index');
    Route::get('/subscription/checkout/{type}', [SubscriptionController::class, 'checkout'])
        ->name('subscription.checkout');
    Route::post('/subscription/pay', [SubscriptionController::class, 'pay'])
        ->name('subscription.pay');

    //Homepage
    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

    // Book Routes
    Route::get('/book', [BookController::class, 'collection'])
        ->name('book.collection');
    Route::get('/genre/{slug}', [BookController::class, 'genreCollection'])
        ->name('book.genre.collection');
    Route::get('/book/{book}', [BookController::class, 'detail'])
        ->name('book.detail');
    Route::get('/book/{book}/reviews', [BookController::class, 'reviews'])
        ->name('book.reviews');
    Route::post('/book/{book}/reviews', [BookController::class, 'reviewStore'])
        ->name('book.review.store');
    Route::put('/book/{book}/reviews/{review}', [BookController::class, 'reviewUpdate'])
        ->name('book.review.update');
    Route::post('/book/{book}/bookmark', [BookController::class, 'bookmark'])
        ->name('book.bookmark');

    // Read Book Routes
    Route::get('/book/{book}/read', [ReadBookController::class, 'show'])
        ->name('book.read');
    Route::post('/save-progress', [ReadBookController::class, 'store']);

    // Bookshelf Routes
    Route::get('/bookshelf', [BookshelfController::class, 'index'])
        ->name('bookshelf.index');
    Route::get('/bookshelf/history', [BookshelfController::class, 'history'])
        ->name('bookshelf.history');
    Route::get('/bookshelf/reviews', [BookshelfController::class, 'reviews'])
        ->name('bookshelf.reviews');
});
