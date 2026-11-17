<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/scan', [ScanController::class, 'index'])->name('scan');
Route::post('/scan', [ScanController::class, 'store'])->name('scan.store');
Route::get('/scan/{scan}', [ScanController::class, 'show'])->name('scan.show');

Route::get('/pencapaian', [AchievementController::class, 'index'])->name('achievements');
