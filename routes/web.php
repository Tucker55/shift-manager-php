<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/shifts/available', [ShiftController::class, 'available'])->name('shifts.available');
    Route::get('/shifts/calendar', [ShiftController::class, 'calendar'])->name('shifts.calendar');
    Route::get('/shifts/mine', [ShiftController::class, 'mine'])->name('shifts.mine');
    Route::get('/shifts/history', [ShiftController::class, 'history'])->name('shifts.history');
    Route::get('/manager/shifts', [ShiftController::class, 'manage'])->name('manager.shifts');
    Route::post('/manager/shifts', [ShiftController::class, 'store'])->name('manager.shifts.store');
    Route::put('/manager/shifts/{shift}', [ShiftController::class, 'update'])->name('manager.shifts.update');
    Route::delete('/manager/shifts/{shift}', [ShiftController::class, 'destroy'])->name('manager.shifts.destroy');
    Route::post('/shifts/{shift}/claim', [ShiftController::class, 'claim'])->name('shifts.claim');
    Route::post('/shifts/{shift}/release', [ShiftController::class, 'release'])->name('shifts.release');

    Route::get('/team', [TeamController::class, 'index'])->name('team.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
