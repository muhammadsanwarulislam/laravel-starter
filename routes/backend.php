<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserManagement\AdminController;
use App\Http\Controllers\Backend\UserManagement\ProfileController;
use App\Http\Controllers\Backend\UserManagement\SuperAdminController;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('super-admin', SuperAdminController::class);
Route::post('users/{userID}/publish', [SuperAdminController::class, 'togglePublish'])->name('users.publish');
Route::post('users/{userID}/block', [SuperAdminController::class, 'toggleBlock'])->name('users.blocked');
Route::resource('admin', AdminController::class);
Route::resource('roles', RoleController::class);
Route::get('profile/', [ProfileController::class, 'index'])->name('profile.index');
Route::post('profile/', [ProfileController::class, 'update'])->name('profile.update');
Route::get('profile/security', [ProfileController::class, 'changePassword'])->name('profile.password.change');
Route::post('profile/security', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
