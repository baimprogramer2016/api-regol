<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntrolSep extends Model
{
    use HasFactory;
    protected $connection = 'odbc';
    protected $table = 'temp_cari_sep_bpjs';
    protected $guarded = ['id'];
    public $timestamps = false;
}
