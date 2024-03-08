<?php

use App\Http\Controllers\TindakanControl;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return 'API REGOL - Smarthubtechnologies V.2';
});

Route::get('/tindakan-aktif', [TindakanControl::class, 'index'])->name('tindakan-aktif');
Route::post('/tindakan-poli-update', [TindakanControl::class, 'update'])->name('tindakan-poli-update');
