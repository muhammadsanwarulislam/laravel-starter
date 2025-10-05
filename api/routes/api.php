<?php

use Repository\User\UserRepository;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Repository\Localization\LocalizationRepository;
use App\Http\Controllers\Localization\LocalizationController;

Route::post(UserRepository::REGISTER_API_ENDPOINT_NAME, [AuthController::class, 'signup']);
Route::post(UserRepository::LOGIN_API_ENDPOINT_NAME, [AuthController::class, 'signin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get(UserRepository::CURRENT_API_ENDPOINT_NAME, [AuthController::class, 'authorizedUserInformation']);
    Route::post(UserRepository::LOGOUT_API_ENDPOINT_NAME, [AuthController::class, 'signout']);
    Route::apiResource(UserRepository::RESOURCE_NAME, UserController::class);

    Route::post('/translations/{locale}', [LocalizationController::class, 'updateTranslation']);
    Route::post('/translations/bulk/{locale}', [LocalizationController::class, 'bulkUpdateTranslations']);
    Route::get('/translations/export/{locale}', [LocalizationController::class, 'exportTranslations']);
    Route::post('/translations/import/{locale}', [LocalizationController::class, 'importTranslations']);
});

Route::get(LocalizationRepository::TRANSLATION_API_ENDPOINT_NAME.'/{locale}', [LocalizationController::class, 'getTranslations']);
Route::get(LocalizationRepository::LANGUAGES_API_ENDPOINT_NAME, [LocalizationController::class, 'getLanguages']);
