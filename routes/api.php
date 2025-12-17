<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BillingController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth endpoints
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    
    // Token management (useful for n8n)
    Route::post('/tokens', [AuthController::class, 'createApiToken']);
    Route::get('/tokens', [AuthController::class, 'tokens']);
    Route::delete('/tokens/{tokenId}', [AuthController::class, 'revokeToken']);
    
    // Your app endpoints
    Route::get('/test', [TestController::class, 'indexTest']);
    Route::get('/test1', [BillingController::class, 'index']);
    Route::get('/test2', [BillingController::class, 'index1']);
    Route::get('/test3', [BillingController::class, 'index2']);
});