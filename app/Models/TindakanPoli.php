<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakanPoli extends Model
{
    use HasFactory;

    protected $connection = 'odbc';
    protected $table = 'tindakan_poli';
    public $timestamps = false;
}
