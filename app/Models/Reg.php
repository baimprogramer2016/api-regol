<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reg extends Model
{
    use HasFactory;

    protected $table = 'reg';

    protected $connection = 'odbc';
    public $timestamps = false;
}
