<?php

namespace App\Repositories\Antrol;

use Illuminate\Http\Response;
use App\Models\Antrol;
use App\Models\AntrolSep;
use App\Models\Reg;
use App\Models\TransLembarKontrol;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AntrolRepository implements AntrolInterface
{

    private $antrol_model, $reg_model, $trans_lembar_model, $antrol_sep_model;


    public function __construct(
        Antrol $antrol_model,
        Reg $reg_model,
        TransLembarKontrol $trans_lembar_model,
        AntrolSep $antrol_sep_model
    ) {

        $this->antrol_model = $antrol_model;
        $this->reg_model = $reg_model;
        $this->trans_lembar_model = $trans_lembar_model;
        $this->antrol_sep_model = $antrol_sep_model;
    }

    public function getPoli()
    {
        return $this->antrol_model
            ->select('reg_no')
            ->where('reg_no', 'A042000035')
            ->get();
    }

    //skdp
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

    //sep
    public function getSepReady($tgl_kunjungan)
    {
        return $this->reg_model::join('pasien', 'reg.no_mr', '=', 'pasien.medrec_no')
            ->join('master_poli', 'reg.kode_poli', '=', 'master_poli.poli_id')
            ->where('reg.kode_poli', '<>', 'UGD01')
            ->where('reg.sep', '=', '')
            ->where('reg.eselon', '=', 'Z3688')
            ->whereNotNull('pasien.no_peserta')
            ->whereNotNull('master_poli.poli_bpjs')
            ->whereDate('reg.tanggal_registrasi', '=', $tgl_kunjungan)
            ->select('pasien.no_peserta', 'reg.sep', 'reg.tanggal_registrasi', 'reg.nama', 'master_poli.poli_bpjs')
            ->get();
    }
    public function truncateTempSep()
    {
        return $this->antrol_sep_model->truncate();
    }
    public function insertSep($param = [])
    {
        return $this->antrol_sep_model->insert($param);
    }

    public function deleteNotNow($tgl_kunjungan)
    {
        return $this->antrol_sep_model->whereDate('tglSep', '<>', $tgl_kunjungan)->delete();
    }

    public function updateSep()
    {
        // select reg.sep,temp_cari_sep_bpjs.* from
        // temp_cari_sep_bpjs,
        // pasien,
        // reg,
        // master_poli
        // WHERE temp_cari_sep_bpjs.noKartu = pasien.no_peserta -done
        // AND pasien.medrec_no = reg.no_mr -done
        // AND reg.kode_poli = master_poli.poli_id  -done
        // AND temp_cari_sep_bpjs.poliTujSep = master_poli.poli_bpjs
        // AND convert(date,reg.tanggal_registrasi) = convert(date,temp_cari_sep_bpjs.tglSep)
        // AND reg.sep = ''

        return $this->reg_model::join('pasien', 'reg.no_mr', '=', 'pasien.medrec_no')
            ->join('master_poli', 'reg.kode_poli', '=', 'master_poli.poli_id')
            ->join('temp_cari_sep_bpjs', 'temp_cari_sep_bpjs.noKartu', '=', 'pasien.no_peserta')
            ->where(DB::raw('LOWER(temp_cari_sep_bpjs.poliTujSep)'), '=', DB::raw('LOWER(master_poli.poli_bpjs)'))
            ->whereDate('reg.tanggal_registrasi', DB::raw('convert(date,temp_cari_sep_bpjs.tglSep)'))
            ->where('reg.sep', '=', '')
            ->update([
                'reg.sep' => DB::raw('temp_cari_sep_bpjs.noSep')
            ]);
    }
}
