<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('pelanggan')->group(function () {
        Route::get('/', [App\Http\Controllers\PelangganController::class, 'index'])->name('pelanggan.index');
    });

    Route::prefix('bengkel')->group(function () {
        Route::get('/', [App\Http\Controllers\BengkelController::class, 'index'])->name('bengkel.index');
        Route::get('/{id}', [App\Http\Controllers\BengkelController::class, 'show'])->name('bengkel.show');
        Route::patch('/{id}/reject', [App\Http\Controllers\BengkelController::class, 'reject'])->name('bengkel.reject');
        Route::patch('/{id}/accept', [App\Http\Controllers\BengkelController::class, 'accept'])->name('bengkel.accept');
    });

    Route::prefix('transaksi')->group(function () {
        Route::get('/', [App\Http\Controllers\TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/{id}', [App\Http\Controllers\TransaksiController::class, 'show'])->name('transaksi.show');
    });
});
