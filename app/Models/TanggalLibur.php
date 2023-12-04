<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalLibur extends Model
{
    use HasFactory;
    protected $connection = 'odbc';

    protected $table = 'tanggal_libur';
}
