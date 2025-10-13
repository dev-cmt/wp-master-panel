<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JwtAuthController;
use App\Http\Controllers\Api\FrodlyController;
use App\Http\Controllers\Api\WebhookController;

// Public routes
Route::post('/register', [JwtAuthController::class, 'register']);
Route::post('/login', [JwtAuthController::class, 'login']);

/**--------------------------------------------------------
 * JWT Protected API
 * --------------------------------------------------------
 */
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [JwtAuthController::class, 'logout']);
    Route::post('/refresh', [JwtAuthController::class, 'refresh']);
    // Frodly
    Route::post('/check-courier', [FrodlyController::class, 'check']);
});
Route::post('/token/check-courier', [FrodlyController::class, 'checkManualy'])
            ->middleware('token.valid');

/**--------------------------------------------------------
 * Woo Commerce API
 * --------------------------------------------------------
 */
Route::post('/wp-orders/webhook', [WebhookController::class, 'orderStore']);
