<?php

namespace App\Repositories\Antrol;

use Illuminate\Http\Response;
use App\Models\Antrol;

class AntrolRepository implements AntrolInterface
{

    private $antrol_model;


    public function __construct(
        Antrol $antrol_model,
    ) {

        $this->antrol_model = $antrol_model;
    }

    public function getPoli()
    {
        return $this->antrol_model
            ->select('reg_no')
            ->where('reg_no', 'A042000035')
            ->get();
    }

    public function insertSkdp($param = [])
    {
        return $this->antrol_model->insert($param);
    }

    public function truncateTemp()
    {
        return $this->antrol_model->truncate();
    }

    public function updateSkdp()
    {
        //         UPDATE trans_lembar_kontrol SET
        // a.skdp_bpjs = c.noSuratKontrol
        // from
        // trans_lembar_kontrol a, reg b , temp_skdp_bpjs c
        // where a.reg_no = b.reg_no
        // AND b.sep = c.noSepAsalKontrol
        // AND a.skdp_bpjs is null
        // AND noSuratKontrol is not null

    }
}
