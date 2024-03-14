<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecommendedProductController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/verify-email-by-link', [VerifyEmailController::class, 'verifyByLink']);
});

Route::middleware(['throttle:60,1', 'auth:sanctum'])->group(function () {
    Route::post('/auth/send-verify-email-by-link', [VerifyEmailController::class, 'sendVerifyEmailByLink']);
    Route::post('/auth/send-verify-email-by-code', [VerifyEmailController::class, 'sendVerifyEmailByCode']);
    Route::post('/auth/verify-email-by-code', [VerifyEmailController::class, 'verifyByCode']);
});

Route::middleware(['throttle:300,1', 'auth:sanctum'])
    ->get('/recommendation', [RecommendedProductController::class, 'index']);
