<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/warga', [\App\Http\Controllers\WargaController::class, 'index']);
Route::post('/warga', [\App\Http\Controllers\WargaController::class, 'store']);
Route::post('/warga/import', [\App\Http\Controllers\WargaController::class, 'documentExcelImport']);
Route::get('/warga/{warga}', [\App\Http\Controllers\WargaController::class, 'show']);
Route::get('/warga/{no_kk}/anggota', [\App\Http\Controllers\WargaController::class, 'showByNoKK']);
Route::put('/warga/{warga}', [\App\Http\Controllers\WargaController::class, 'update']);
Route::delete('/warga/{warga}', [\App\Http\Controllers\WargaController::class, 'destroy']);

Route::get('/statistik-umur', [\App\Http\Controllers\StaticWargaController::class, 'hitungStatistikUmur']);
Route::get('/statistik-warga-rt', [\App\Http\Controllers\StaticWargaController::class, 'hitungWargaRT']);
Route::get('/statistik-agama-warga', [\App\Http\Controllers\StaticWargaController::class, 'hitungAgamaPerRt']);
Route::get('/statistik-jenis-kelamin-warga', [\App\Http\Controllers\StaticWargaController::class, 'hitungJumlahJenisKelaminPerRT']);
Route::get('/statistik-jenis-kawin-warga', [\App\Http\Controllers\StaticWargaController::class, 'hitungStatusKawinPerRT']);
Route::get('/statistik-kk-pj-warga', [\App\Http\Controllers\StaticWargaController::class, 'hitungStatusAnggotaPerRT']);
Route::get('/statistik-status-sosial-warga', [\App\Http\Controllers\StaticWargaController::class, 'hitungStatusSosialPerRT']);
Route::get('/statistik-status-warga', [\App\Http\Controllers\StaticWargaController::class, 'hitungStatusWargaPerRT']);
Route::get('/statistik-status-pekerjaan-warga', [\App\Http\Controllers\StaticWargaController::class, 'hitungStatusPekerjaanPerRT']);

Route::get('/pengurus', [\App\Http\Controllers\PengurusController::class, 'index']);
Route::post('/pengurus', [\App\Http\Controllers\PengurusController::class, 'store']);
Route::get('/pengurus/{pengurus}', [\App\Http\Controllers\PengurusController::class, 'show']);
Route::put('/pengurus/{pengurus}', [\App\Http\Controllers\PengurusController::class, 'update']);
Route::delete('/pengurus/{pengurus}', [\App\Http\Controllers\PengurusController::class, 'destroy']);
