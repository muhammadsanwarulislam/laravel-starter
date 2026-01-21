<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/login/otp', [App\Http\Controllers\Api\AuthController::class, 'requestLoginOtp']);
    Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/password/forgot', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
});

Route::prefix('otp')->group(function () {
    Route::post('/resend', [App\Http\Controllers\Api\AuthController::class, 'resendOtp']);
});

//Localization Routes
Route::get('/languages', [App\Http\Controllers\Api\LocalizationController::class, 'getLanguages']);
Route::get('/locale/current', [App\Http\Controllers\Api\LocalizationController::class, 'getCurrentLocale']);
Route::get('/translations/ui', [App\Http\Controllers\Api\LocalizationController::class, 'getUiTranslations']);
Route::get('/translations/ui/{key}', [App\Http\Controllers\Api\LocalizationController::class, 'getUiTranslation']);

// Protected routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    // OTP Verification Route
    Route::prefix('otp')->group(function () {
        Route::post('/verify', [App\Http\Controllers\Api\AuthController::class, 'verifyOtpAndResponse']);
    });

    // Auth routes
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::put('/change-password', [App\Http\Controllers\Api\AuthController::class, 'changePassword']);

    // Profile
    Route::get('/profile', [App\Http\Controllers\Api\UserController::class, 'profile']);
    Route::put('/profile', [App\Http\Controllers\Api\UserController::class, 'updateProfile']);

    // User Management (requires permissions)
    Route::prefix('users')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\UserController::class, 'index'])->middleware('permission:view-users');
        Route::post('/', [App\Http\Controllers\Api\UserController::class, 'store'])->middleware('permission:create-users');
        Route::get('/{user}', [App\Http\Controllers\Api\UserController::class, 'show'])->middleware('permission:view-users');
        Route::put('/{user}', [App\Http\Controllers\Api\UserController::class, 'update'])->middleware('permission:edit-users');
        Route::delete('/{user}', [App\Http\Controllers\Api\UserController::class, 'destroy'])->middleware('permission:delete-users');
        Route::put('/{user}/status', [App\Http\Controllers\Api\UserController::class, 'updateStatus'])->middleware('permission:edit-users');
        Route::post('/{user}/roles', [App\Http\Controllers\Api\UserController::class, 'assignRoles'])->middleware('permission:edit-users');
    });

    // Role Management
    Route::apiResource('roles', App\Http\Controllers\Api\RoleController::class)->middleware('permission:view-roles');
    Route::post('/roles/{role}/permissions', [App\Http\Controllers\Api\RoleController::class, 'assignPermissions'])->middleware('permission:edit-roles');

    // Permission Management
    Route::prefix('permissions')->middleware('permission:view-permissions')->group(function () {
        Route::get('/', [App\Http\Controllers\Api\PermissionController::class, 'index']);
        Route::get('/modules', [App\Http\Controllers\Api\PermissionController::class, 'getModules']);
        Route::get('/module/{module}', [App\Http\Controllers\Api\PermissionController::class, 'getByModule']);
        Route::post('/sync', [App\Http\Controllers\Api\PermissionController::class, 'sync'])->middleware('permission:manage-permissions');
    });

    // Localization Management (admin only)
    Route::prefix('localization')->middleware('permission:view-translations')->group(function () {
        Route::post('/locale/set', [App\Http\Controllers\Api\LocalizationController::class, 'setLocale']);
        Route::post('/translations/ui', [App\Http\Controllers\Api\LocalizationController::class, 'storeUiTranslation'])->middleware('permission:edit-translations');
        Route::get('/translations/content/{model}/{id}', [App\Http\Controllers\Api\LocalizationController::class, 'getContentTranslations']);
    });

    // File Management 
    Route::prefix('files')->middleware('permission:view-files')->group(function () {
        // File routes would go here
    });
});
