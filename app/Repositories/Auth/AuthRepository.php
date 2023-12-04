<?php

namespace App\Repositories\Auth;

use Illuminate\Http\Response;
use App\Models\Auth;

class AuthRepository implements AuthInterface
{

    private $auth_model;

    private $id, $telpon, $nama;

    public function __construct(
        Auth $auth_model,
    ) {

        $this->auth_model = $auth_model;
    }

    public function signUp($param_array)
    {
        $this->id = $param_array['id'];
        $this->telpon = $param_array['telpon'];
        $this->nama = $param_array['nama'];

        $check = $this->auth_model->where('telpon', $this->telpon)->get()->count();
        if ($check > 0) {
            return true;
        } else {
            $this->auth_model->insert($param_array);
            return false;
        }
    }
    public function login($param_array)
    {
        $this->id = $param_array['id'];
        $this->telpon = $param_array['telpon'];

        $check = $this->auth_model
            ->where('id', $this->id)
            ->where('telpon', $this->telpon)
            ->get()
            ->count();
        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }
}