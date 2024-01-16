<?php

namespace App\Repositories\Antrol;

use Illuminate\Http\Response;
use App\Models\Antrol;
use App\Models\Reg;
use App\Models\TransLembarKontrol;
use Illuminate\Support\Facades\DB;

class AntrolRepository implements AntrolInterface
{

    private $antrol_model, $reg_model, $trans_lembar_model;


    public function __construct(
        Antrol $antrol_model,
        Reg $reg_model,
        TransLembarKontrol $trans_lembar_model,
    ) {

        $this->antrol_model = $antrol_model;
        $this->reg_model = $reg_model;
        $this->trans_lembar_model = $trans_lembar_model;
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
        // return DB::connection('odbc')->select("UPDATE trans_lembar_kontrol SET
        // a.skdp_bpjs = c.noSuratKontrol
        // from
        // trans_lembar_kontrol a, reg b , temp_skdp_bpjs c
        // where a.reg_no = b.reg_no
        // AND b.sep = c.noSepAsalKontrol
        // AND a.skdp_bpjs is null
        // AND noSuratKontrol is not null
        // AND c.terbitSEP ='Belum'");

        return $this->trans_lembar_model::join('reg', 'trans_lembar_kontrol.reg_no', '=', 'reg.reg_no')
            ->join('temp_skdp_bpjs', 'reg.sep', '=', 'temp_skdp_bpjs.noSepAsalKontrol')
            ->whereNull('trans_lembar_kontrol.skdp_bpjs')
            ->whereNotNull('temp_skdp_bpjs.noSuratKontrol')
            ->where('temp_skdp_bpjs.terbitSEP', 'Belum')
            ->update([
                'trans_lembar_kontrol.skdp_bpjs' => DB::raw('temp_skdp_bpjs.noSuratKontrol'),
                'trans_lembar_kontrol.tgl_kontrol' => DB::raw('temp_skdp_bpjs.tglRencanaKontrol')
            ]);
    }
}
