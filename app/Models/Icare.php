<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icare extends Model
{
    use HasFactory;

    protected $connection = 'odbc';
    protected $table = 'icare_bpjs';
    protected $guarded = ['ID'];

    public $timestamps = false;
}
