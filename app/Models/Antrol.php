<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrol extends Model
{
    use HasFactory;
    protected $connection = 'odbc_cileungsi';
    protected $table = 'temp_skdp_bpjs';
    protected $guarded = ['id'];
}
