<?php

use Repository\User\UserRepository;
use Illuminate\Support\Facades\Route;
use Repository\Language\LanguageRepository;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfileController;
use Repository\Localization\LocalizationRepository;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\FileManager\FileManagerController;
use App\Http\Controllers\Localization\LocalizationController;

Route::post(UserRepository::REGISTER_API_ENDPOINT_NAME, [AuthController::class, 'signup']);
Route::post(UserRepository::LOGIN_API_ENDPOINT_NAME, [AuthController::class, 'signin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get(UserRepository::CURRENT_API_ENDPOINT_NAME, [AuthController::class, 'authorizedUserInformation']);
    Route::post(UserRepository::LOGOUT_API_ENDPOINT_NAME, [AuthController::class, 'signout']);
    Route::apiResource(UserRepository::RESOURCE_NAME, UserController::class);
    Route::apiResource(LanguageRepository::RESOURCE_NAME, LanguageController::class);

    // Profile routes
    Route::apiResource('profiles', ProfileController::class);
    Route::get('profiles/user/{userId}', [ProfileController::class, 'getByUser']);
    
    // File manager routes
    Route::apiResource('files', FileManagerController::class);
    Route::get('files/user/{userId}', [FileManagerController::class, 'getByUser']);
    Route::get('files/{fileManager}/download', [FileManagerController::class, 'download']);
    
    // Translation routes with locale parameter
    Route::post('translations/{locale}', [LocalizationController::class, 'store']);
    Route::put('translations/{locale}', [LocalizationController::class, 'update']);
    Route::delete('translations/{locale}', [LocalizationController::class, 'destroy']);
    Route::post('translations/{locale}/bulk', [LocalizationController::class, 'bulkStore']);
});

// Public routes
Route::get('translations/{locale}', [LocalizationController::class, 'index']);
Route::apiResource(LanguageRepository::RESOURCE_NAME, LanguageController::class)->only(['index']);