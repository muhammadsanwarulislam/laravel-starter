<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth']);

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('roles', RoleController::class);
