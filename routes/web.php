<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteResultsController;
use Illuminate\Support\Facades\Route;

Route::get('/get-token', [AuthController::class, 'getToken'])->name('get-token');

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

    Route::get('/hasil-vote', [VoteResultsController::class, 'index'])->name('pages.vote');
    Route::get('/voting/results-api', [VoteResultsController::class, 'getResultsApi'])->name('voting.results.api');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/candidate', fn() => view('admin.candidate', ['title' => 'Candidate']))->name('candidate');
    Route::get('/user', fn() => view('admin.users', ['title' => 'User Management']))->name('user');
    Route::get('/category', fn() => view('admin.category', ['title' => 'Category']))->name('category');
});