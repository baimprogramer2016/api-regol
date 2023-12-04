<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PenjaminController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\TanggalController;
use App\Http\Controllers\TesController;
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

Route::get('/', function () {
    return 'API REGOL - Smarthubtechnologies -_-';
});

Route::get('/tes', [TesController::class, 'index']);

// Route::get('/dokter/{dokter_id}', [DokterController::class, 'getDokterId'])->name('dokter-id');
Route::get('/dokter', [DokterController::class, 'getDokter']);
Route::post('/dokter', [DokterController::class, 'getDokter']);
Route::get('/jadwal-dokter', [DokterController::class, 'getJadwalDokter']);
Route::post('/jadwal-dokter', [DokterController::class, 'getJadwalDokter']);
Route::get('/poli', [PoliController::class, 'getPoli']);
Route::post('/poli', [PoliController::class, 'getPoli']);
Route::get('/nama-unit', [PoliController::class, 'getNamaUnit']);
Route::post('/nama-unit', [PoliController::class, 'getNamaUnit']);
Route::get('/penjamin', [PenjaminController::class, 'getPenjamin']);
Route::post('/penjamin', [PenjaminController::class, 'getPenjamin']);
Route::post('/verifikasi', [PasienController::class, 'procVerifikasi']);
Route::get('/tanggal-libur', [TanggalController::class, 'getTanggalLibur']);
Route::post('/tanggal-libur', [TanggalController::class, 'getTanggalLibur']);
Route::post('/pasien', [PasienController::class, 'getPasienByMr']);
Route::post('/antrian', [AntrianController::class, 'getAntrian']);
Route::post('/signup', [AuthController::class, 'signUp']);
Route::post('/login', [AuthController::class, 'login']);
