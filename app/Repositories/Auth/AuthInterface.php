<?php

namespace App\Repositories\Auth;

interface AuthInterface
{
    public function signUp($param_array);
    public function login($param_array);

    public function editProfil($param_array);
}
