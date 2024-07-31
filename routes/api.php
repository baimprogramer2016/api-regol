<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AntrolController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BpjsTesController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\IcareController;
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

// regol bontang
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
Route::post('/edit-profil', [AuthController::class, 'editProfil']);
//
//antrol
Route::group(['prefix' => 'antrol'], function () {
    Route::get('/bpjs-signature', [AntrolController::class, 'index'])->name('bpjs-signature');
    Route::get('/bpjs-poli', [AntrolController::class, 'poli'])->name('bpjs-poli');
    Route::get('/bpjs-update-skdp/{filter}', [AntrolController::class, 'udpateSkdp'])->name('bpjs-update-skdp');
    Route::get('/bpjs-cari-sep', [AntrolController::class, 'cariSep'])->name('bpjs-cari-sep');
    Route::get('/bpjs-cari-sep-manual', [AntrolController::class, 'cariSepManual'])->name('bpjs-cari-sep-manual');
    Route::get('/bpjs-cari-sep-casemix', [AntrolController::class, 'cariSepCasemix'])->name('bpjs-cari-sep-casemix');
});
Route::group(['prefix' => 'icare'], function () {
    Route::get('/bpjs-icare', [IcareController::class, 'index'])->name('bpjs-icare');
    Route::get('/bpjs-jadwal-hfis/{poli}/{tanggal}', [IcareController::class, 'jadwal_hfis'])->name('bpjs-jadwal-hfis');
});

Route::group(['prefix' => 'bontang'], function () {
    Route::get('/bpjs-tes-signature', [BpjsTesController::class, 'index'])->name('bpjs-tes-signature');
    Route::get('/bpjs-tes', [BpjsTesController::class, 'tesApi'])->name('bpjs-poli');
});
