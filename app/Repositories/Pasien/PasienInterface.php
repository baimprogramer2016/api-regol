<?php


namespace App\Repositories\Pasien;

interface PasienInterface
{
    public function procVerifikasi($param_array);

    public function getPasienByMr($nomr);
}
