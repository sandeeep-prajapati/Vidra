<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocsController;

Route::get('/', function () {
    return view('core-package::landing');
})->name('home');

// Authentication
Route::middleware('web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Dashboard
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Package routes are loaded via their Service Providers

Route::get('/manual/{page?}', [DocsController::class, 'show'])->where('page', '.*');
// Route::get('/manual/{page?}', function(){
//     dd("hello");
// });
