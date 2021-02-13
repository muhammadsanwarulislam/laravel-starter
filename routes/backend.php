<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserManagement\SuperAdminController;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('super-admin', SuperAdminController::class);
Route::post('users/{userID}/publish', [SuperAdminController::class, 'togglePublish'])->name('users.publish');
Route::post('users/{userID}/block', [SuperAdminController::class, 'toggleBlock'])->name('users.blocked');
Route::resource('roles', RoleController::class);