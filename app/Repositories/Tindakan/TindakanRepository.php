<?php

namespace App\Repositories\Tindakan;

use App\Models\Pasien;
use App\Models\TindakanPoli;

class TindakanRepository implements TindakanInterface
{

    private $model;

    public function __construct(
        TindakanPoli $tindakan_model
    ) {
        $this->model = $tindakan_model;
    }
    public function getTindakanPoli($search_poli, $search_tindakan)
    {
        if ($search_poli || $search_tindakan) {
            return $this->model::join("master_tindakan", "master_tindakan.tindakan_id", "=", "tindakan_poli.tindakan_id")
                ->join("master_poli", "master_poli.poli_id", "=", "tindakan_poli.poli_id")
                ->when($search_poli != null, function ($q) use ($search_poli) {
                    return $q->Where('master_poli.poli_name', 'like', '%' . $search_poli . '%');
                })
                ->when($search_tindakan != null, function ($q) use ($search_tindakan) {
                    return $q->where('master_tindakan.tindakan_desc', 'like', '%' . $search_tindakan . '%');
                })
                ->select('master_poli.poli_id', 'master_poli.poli_name', 'master_tindakan.tindakan_desc', 'master_tindakan.tindakan_id', 'tindakan_poli.aktif')
                ->get();
        } else {
            return $this->model::join("master_tindakan", "master_tindakan.tindakan_id", "=", "tindakan_poli.tindakan_id")
                ->join("master_poli", "master_poli.poli_id", "=", "tindakan_poli.poli_id")
                ->select('master_poli.poli_id', 'master_poli.poli_name', 'master_tindakan.tindakan_desc', 'master_tindakan.tindakan_id', 'tindakan_poli.aktif')
                ->get()->take(10);
        }
    }

    public function updateTIndakan($poli_id, $tindakan_id, $aktif)
    {
        return $this->model::where('poli_id', $poli_id)
            ->where('tindakan_id', $tindakan_id)
            ->update([
                'aktif' => $aktif
            ]);
    }
}
