<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjamin extends Model
{
    use HasFactory;

    protected $connection = 'odbc';

    protected $table = 'ku_kode_eselon';
}
