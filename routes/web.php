<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAchievementController;
use App\Http\Controllers\AdminRewardController;
use App\Http\Controllers\AdminScanController;
use App\Http\Controllers\AdminLeaderboardController;

// Belum login: login, register, guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');

    Route::post('/guest', [AuthController::class, 'guest'])->name('guest');
});

// Sudah login: semua fitur utama
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.home');

    Route::get('/scan', [ScanController::class, 'index'])->name('scan');
    Route::post('/scan', [ScanController::class, 'store'])->name('scan.store');
    Route::get('/scan/{scan}', [ScanController::class, 'show'])->name('scan.show');

    Route::get('/tantangan', [ChallengeController::class, 'index'])->name('challenges');

    Route::get('/riwayat', [HistoryController::class, 'index'])->name('history');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    Route::get('/pencapaian', [AchievementController::class, 'index'])->name('achievements');

    Route::get('/hadiah', [RewardController::class, 'index'])->name('rewards');
    Route::post('/hadiah/{reward}/tukar', [RewardController::class, 'redeem'])->name('rewards.redeem');

    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    
});

//admin: dashboard admin
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

            Route::get('/users', [AdminUserController::class, 'index'])
    ->name('users');

Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])
    ->name('users.role');

    Route::patch('/users/{user}/progress', [AdminUserController::class, 'updateProgress'])
    ->name('users.progress');

    Route::get('/achievements', [AdminAchievementController::class, 'index'])
    ->name('achievements');

Route::patch('/achievements/{user}/{achievement}/unlock', [AdminAchievementController::class, 'unlock'])
    ->name('achievements.unlock');

Route::delete('/achievements/{user}/{achievement}/reset', [AdminAchievementController::class, 'reset'])
    ->name('achievements.reset');

    Route::get('/rewards', [AdminRewardController::class, 'index'])
    ->name('rewards');

Route::post('/rewards', [AdminRewardController::class, 'store'])
    ->name('rewards.store');

Route::patch('/rewards/{reward}', [AdminRewardController::class, 'update'])
    ->name('rewards.update');

Route::patch('/rewards/{reward}/toggle', [AdminRewardController::class, 'toggle'])
    ->name('rewards.toggle');

Route::delete('/rewards/{reward}', [AdminRewardController::class, 'destroy'])
    ->name('rewards.destroy');

    Route::get('/scans', [AdminScanController::class, 'index'])
    ->name('scans');

Route::delete('/scans/{scan}', [AdminScanController::class, 'destroy'])
    ->name('scans.destroy');

    Route::get('/leaderboard', [AdminLeaderboardController::class, 'index'])
    ->name('leaderboard');
    });
