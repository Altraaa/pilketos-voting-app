<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// langsung ke halaman home
Route::get('/', function () {
    return view('pages.home', [
        'title' => 'Dashboard',
    ]);
})->name('home');

// login (kalau nanti mau diaktifin lagi)
Route::get('/login', [AuthController::class, 'view'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout']);

// dashboard
Route::get('/home', function () {
    return view('pages.home', [
        'title' => 'Dashboard',
    ]);
})->name('pages.home');

// tentang kami
Route::get('/tentang-kami', function () {
    return view('pages.about', [
        'title' => 'Tentang Kami',
    ]);
})->name('about');