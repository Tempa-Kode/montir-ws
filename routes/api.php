<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/ping', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'Hi Aprilmen, your API is working fine!'
    ], 200);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profil', [App\Http\Controllers\Api\ProfilController::class, 'getProfil'])->middleware('auth:sanctum');

Route::prefix('bengkel-management')->middleware('auth:sanctum')->group(function () {
    Route::post('/simpan-data-bengkel', [App\Http\Controllers\Api\BengkelController::class, 'simpanDataBengkel']);
    Route::get('/cek-validasi', [App\Http\Controllers\Api\BengkelController::class, 'cekStatusValidasiBengkel']);
    Route::post('/daftar-layanan', [App\Http\Controllers\Api\BengkelController::class, 'daftarLayananBengkel']);
});
