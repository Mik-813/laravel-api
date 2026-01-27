<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/auth/verify', [AuthController::class, 'verify']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/email/send-verification', [AuthController::class, 'sendVerificationEmail']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/stats', function () {
        return response()->json(['message' => 'Admin statistics dashboard']);
    });
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});
