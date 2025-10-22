<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Get current authenticated user
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // User Routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/users/generate-bulk', [UserController::class, 'generateBulk']);
    Route::post('/users/delete-multiple', [UserController::class, 'destroyMultiple']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Category Routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/active', [CategoryController::class, 'active']);
    Route::get('/categories/with-candidates', [CategoryController::class, 'withCandidates']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/categories/slug/{slug}', [CategoryController::class, 'showBySlug']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::put('/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus']);
    Route::post('/categories/update-order', [CategoryController::class, 'updateOrder']);
    Route::post('/categories/bulk-delete', [CategoryController::class, 'bulkDelete']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Candidate Routes
    Route::get('/candidates', [CandidateController::class, 'index']);
    Route::post('/candidates', [CandidateController::class, 'store']);
    Route::get('/candidates/{id}', [CandidateController::class, 'show']);
    Route::put('/candidates/{id}', [CandidateController::class, 'update']);
    Route::post('candidates/{id}/upload-image', [CandidateController::class, 'uploadImage']);
    Route::delete('/candidates/{id}', [CandidateController::class, 'destroy']);
    Route::get('/candidates/category/{categoryId}', [CandidateController::class, 'byCategory']);
    
    // Vote Routes
    Route::get('/votes', [VoteController::class, 'index']);
    Route::post('/votes', [VoteController::class, 'store']);
    Route::get('/votes/check', [VoteController::class, 'checkUserVote']);
    Route::get('/candidates/{candidateId}/votes', [VoteController::class, 'showByCandidate']);
});