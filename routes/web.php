<?php

use App\Http\Controllers\TindakanControl;
use App\Models\Antrol;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::get('/chunk', function () {


    $batchSize = 10;

    // Hitung jumlah halaman yang diperlukan berdasarkan jumlah total data dan ukuran batch
    $totalData = 100;
    $totalPages = ceil($totalData / $batchSize);
    $coll = [];
    // Looping untuk mendapatkan setiap halaman data
    for ($page = 1; $page <= $totalPages; $page++) {
        $offset = ($page - 1) * $batchSize;

        // Mengambil data dari tabel 'tes' dengan menggunakan query raw
        $data = DB::connection('odbc')
            ->select("SELECT * FROM reg LIMIT $batchSize OFFSET $offset");
        array_push($coll, $data);
    }
    return $coll;
});
