<?php

namespace App\Repositories\Penjamin;

use App\Models\Penjamin;

class PenjaminRepository implements PenjaminInterface
{

    private $model;

    private $kode_eselon, $deskripsi = null;
    public function __construct(
        Penjamin $penjamin_model
    ) {
        $this->model = $penjamin_model;
    }

    public function getPenjamin($param_array)
    {

        if (array_key_exists('kode_eselon', $param_array)) {
            $this->kode_eselon = $param_array['kode_eselon'];
        }
        if (array_key_exists('deskripsi', $param_array)) {
            $this->deskripsi = $param_array['deskripsi'];
        }

        // return $this->kode_eselon;
        return $this->model
            ->select('kode_eselon', 'deskripsi', 'tanggungan', 'alamat_jalan', 'alamat_kota', 'alamat_telpon')
            ->where('kode_eselon', '<>', '-')
            ->when($this->kode_eselon != null, function ($q) {
                return $q->where('kode_eselon', $this->kode_eselon);
            })
            ->when($this->deskripsi != null, function ($q) {
                return $q->where('deskripsi', 'like', '%' . $this->deskripsi . '%');
            })
            ->orderBy('kode_eselon', 'ASC')
            ->get();
    }
}
