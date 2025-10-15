<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// landing page
Route::get('/', [AuthController::class, 'view'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout']);

// dashboard
Route::get('/home', function () {
    return view('pages.home', [
        'title' => 'Dashboard',
    ]);
})->name('pages.home');
