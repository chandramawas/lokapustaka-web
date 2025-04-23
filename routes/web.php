<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelloWorldController;
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

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('register', [AuthController::class, 'showRegisterForm'])
    ->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('login', [AuthController::class, 'showLoginForm'])
    ->name('login');
Route::post('login', [AuthController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('welcome');
})->name('logout');

Route::get('/', function () {
    return view('home');
})->name('home')->middleware('auth');