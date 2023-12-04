<?php

namespace App\Repositories\Pasien;

use App\Models\Pasien;

class PasienRepository implements PasienInterface
{

    private $model;

    private $key, $val, $tgl_lahir = null;
    public function __construct(
        Pasien $pasien_model
    ) {
        $this->model = $pasien_model;
    }

    public function procVerifikasi($param_array)
    {

        # pilihan bisa mr, ktp, bpjs
        $this->key =  array_keys($param_array)[0];
        $this->val =  $param_array[$this->key];

        # tanggal Lahir wajib
        $this->tgl_lahir = $param_array['tgl_lahir'];

        return $this->model
            ->select('medrec_no', 'nama', 'tanggal_lahir', 'alamat', 'no_ktp', 'no_peserta', 'no_polis')
            ->where('tanggal_lahir', $this->tgl_lahir)
            ->when($this->key != null, function ($q) {
                return $q->where($this->key, $this->val);
            })
            ->get();
    }

    public function getPasienByMr($nomr)
    {
        return $this->model->where('medrec_no', $nomr)->get();
    }
}
