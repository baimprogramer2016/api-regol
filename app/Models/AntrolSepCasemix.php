<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AntrolSepCasemix extends Model
{
    use HasFactory;

    protected $connection = 'odbc';
    protected $table = 'temp_cari_sep_casemix_bpjs';
    protected $guarded = ['id'];
    public $timestamps = false;
}
