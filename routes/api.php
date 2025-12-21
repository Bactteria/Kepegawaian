<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ManagementApiController;
use App\Http\Controllers\Api\KaryawanApiController;
use App\Http\Controllers\Api\AbsenApiController;

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

// Endpoint struktur manager-staff (untuk demo koneksi API dari Blade)
Route::get('/management-level', [ManagementApiController::class, 'index']);

// Endpoint data karyawan (daftar & detail)
Route::get('/karyawan', [KaryawanApiController::class, 'index']);
Route::get('/karyawan/{id}', [KaryawanApiController::class, 'show']);

// Endpoint statistik kehadiran harian (jumlah user yang sudah check-in hari ini)
Route::get('/absen/kehadiran-hari-ini', [AbsenApiController::class, 'kehadiranHariIni']);

// Contoh endpoint user yang tetap dilindungi Sanctum (bila dikonfigurasi)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
