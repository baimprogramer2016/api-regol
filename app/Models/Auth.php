<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    use HasFactory;

    protected $connection = 'odbc';

    protected $table = 'user_online';
    public $timestamps = false;
    protected $keyType= 'string';
}
