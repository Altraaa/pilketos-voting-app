<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'view'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('pages.home', [
            'title' => 'Dashboard',
        ]);
    })->name('home');

    Route::get('/home', function () {
        return view('pages.home', [
            'title' => 'Dashboard',
        ]);
    })->name('pages.home');

    Route::get('/tentang-kami', function () {
        return view('pages.about', [
            'title' => 'Tentang Kami',
        ]);
    })->name('about');

    Route::get('/hasil-vote', function () {
        return view('pages.vote', [
            'title' => 'Hasil Vote',
        ]);
    })->name('pages.vote');
});
