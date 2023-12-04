<?php

namespace App\Repositories\Tanggal;

use Illuminate\Http\Response;
use App\Models\TanggalLibur;

class TanggalRepository implements TanggalInterface
{

    private $model;
    public function __construct(
        TanggalLibur $tanggal_libur_model
    ) {
        $this->model = $tanggal_libur_model;
    }

    public function getTanggalLibur($tanggal)
    {
        return $this->model
            ->when($tanggal != null, function ($q) use ($tanggal) {
                return $q->where('tanggal', $tanggal);
            })->get();
    }
}
