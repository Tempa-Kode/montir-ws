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
    Route::get('/layanan-bengkel', [App\Http\Controllers\Api\LayananBengkelContoller::class, 'index']);
    Route::post('/daftar-layanan', [App\Http\Controllers\Api\LayananBengkelContoller::class, 'daftarLayananBengkel']);
    Route::put('/update-layanan/{id}', [App\Http\Controllers\Api\LayananBengkelContoller::class, 'updateLayananBengkel']);
    Route::delete('/hapus-layanan/{id}', [App\Http\Controllers\Api\LayananBengkelContoller::class, 'hapusLayananBengkel']);

    Route::prefix('montir')->group(function () {
        Route::get('/daftar-montir', [App\Http\Controllers\Api\MontirBengkelController::class, 'daftarMontirBengkel']);
        Route::post('/tambah-montir', [App\Http\Controllers\Api\MontirBengkelController::class, 'tambahMontirBengkel']);
        Route::delete('/hapus-montir/{id}', [App\Http\Controllers\Api\MontirBengkelController::class, 'hapusMontirBengkel']);
    });

    Route::prefix('order-layanan')->group(function () {
        Route::get('/list-order-bengkel', [App\Http\Controllers\Api\OrderLayananController::class, 'listOrderLayananBengkel']);
        Route::put('/assign-montir/{order_id}', [App\Http\Controllers\Api\OrderLayananController::class, 'assignMontirToOrderLayanan']);
        Route::put('/update/kelokasi/{order_id}', [App\Http\Controllers\Api\MontirLayananController::class, 'menujuKelokasi']);
        Route::put('/update/kerjakan/{order_id}', [App\Http\Controllers\Api\MontirLayananController::class, 'mengerjakanService']);
        Route::put('/update/selesai/{order_id}', [App\Http\Controllers\Api\MontirLayananController::class, 'menyelesaikanPekerjaan']);
    });
});

Route::prefix('public')->group(function () {
    Route::get('/cari-bengkel', [App\Http\Controllers\Api\PublicController::class, 'cariBengkelTedekatBerdasarkanJenisLayanan']);
    Route::get('/detail-bengkel/{id}', [App\Http\Controllers\Api\PublicController::class, 'detailBengkel']);
});

Route::prefix('order-layanan')->middleware('auth:sanctum')->group(function () {
    Route::post('/buat-order', [App\Http\Controllers\Api\OrderLayananController::class, 'buatOrderLayanan']);
    Route::get('/order-history', [App\Http\Controllers\Api\OrderLayananController::class, 'listOrderLayanan']);
});
