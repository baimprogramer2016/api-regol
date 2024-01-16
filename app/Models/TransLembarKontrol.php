<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransLembarKontrol extends Model
{
    use HasFactory;
    protected $connection = 'odbc';
    protected $table = 'trans_lembar_kontrol';

    public $timestamps = false;
}
