<?php

namespace App\Repositories\Poli;

use Illuminate\Http\Response;
use App\Models\Poli;

class PoliRepository implements PoliInterface
{

    private $model;
    public function __construct(
        Poli $poli_model
    ) {
        $this->model = $poli_model;
    }

    public function getPoli($poli_id)
    {
        return $this->model
            ->select('poli_id', 'poli_name')
            ->when($poli_id != null, function ($q) use ($poli_id) {
                return $q->where('poli_id', $poli_id);
            })->get();
    }


    public function getNamaUnit($tipe_poli)
    {
        return $this->model
            ->select('poli_id', 'poli_name', 'tipe_poli')
            ->whereIn('tipe_poli', ['UM', 'FIS', 'GML', 'KJ', 'MCU', 'GIZ', 'SPS', 'RWT', 'HD', 'KIA'])
            ->where('aktif', 'Y')
            ->whereNotIn('poli_name', ['MOBIL AMBULANCE', 'INSTALASI GIZI', 'PEMULASARAN JENAZAH', 'INSTALASI RAWAT JALAN', 'INSTALASI RAWAT INAP'])
            ->when($tipe_poli != null, function ($q) use ($tipe_poli) {
                return $q->where('tipe_poli', $tipe_poli);
            })->orderBy('poli_id', 'ASC')
            ->get();
    }
}
