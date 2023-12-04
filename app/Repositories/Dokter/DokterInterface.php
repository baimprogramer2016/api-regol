<?php

namespace App\Repositories\Dokter;

interface DokterInterface
{
    public function getDokter($dokter_id);

    public function getJadwalDokter($param_array);
}
