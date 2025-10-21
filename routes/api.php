<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoteController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Candidate Routes
    Route::get('/candidates', [CandidateController::class, 'index']);
    Route::post('/candidates', [CandidateController::class, 'store']);
    Route::get('/candidates/{id}', [CandidateController::class, 'show']);
    Route::put('/candidates/{id}', [CandidateController::class, 'update']);
    Route::post('candidates/{id}/upload-image', [CandidateController::class, 'uploadImage']);
    Route::delete('/candidates/{id}', [CandidateController::class, 'destroy']);
    
    // Vote Routes
    Route::get('/votes', [VoteController::class, 'index']);
    Route::post('/votes', [VoteController::class, 'store']);
    Route::get('/candidates/{candidateId}/votes', [VoteController::class, 'showByCandidate']);
});