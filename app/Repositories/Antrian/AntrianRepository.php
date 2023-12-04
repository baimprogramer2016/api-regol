<?php

namespace App\Repositories\Antrian;

use Illuminate\Http\Response;
use App\Models\Antrian;

class AntrianRepository implements AntrianInterface
{

    private $antrian_model;

    private $checkin, $telpon, $terlayani = null;

    public function __construct(
        Antrian $antrian_model,
    ) {

        $this->antrian_model = $antrian_model;
    }


    public function getAntrian($param_array)
    {
        if (array_key_exists('checkin', $param_array)) {
            $this->checkin = $param_array['checkin'];
        }
        if (array_key_exists('telpon', $param_array)) {
            $this->telpon = $param_array['telpon'];
        }
        if (array_key_exists('terlayani', $param_array)) {
            $this->terlayani = $param_array['terlayani'];
        }

        $query = $this->antrian_model->query();
        # 0 Kolom
        $query = $query->select('antriandokter.tanggal', 'antriandokter.dokter_id', 'dokter.nama_dokter', 'antriandokter.antrian', 'master_poli.poli_name', 'antriandokter.nomr', 'antriandokter.nama', 'antriandokter.tgl_lahir', 'antriandokter.diregister', 'antriandokter.t_call', 'antriandokter.kode', 'antriandokter.no_antri', 'antriandokter.t_reg', 'antriandokter.waktu', 'antriandokter.bayar', 'antriandokter.no_urut_poli', 'antriandokter.printdok', 'antriandokter.update_by', 'antriandokter.tracer', 'antriandokter.qrcode', 'antriandokter.verifikasi', 'antriandokter.keterangan', 'antriandokter.no_hp', 'antriandokter.alamat', 'antriandokter.tgl_online', 'antriandokter.checkin', 'antriandokter.checkin_waktu', 'antriandokter.tanggal2', 'antriandokter.terlayani', 'antriandokter.lastupdate', 'antriandokter.nomorkartu', 'antriandokter.nik', 'antriandokter.nomorref', 'antriandokter.jenisref', 'antriandokter.jenisreq', 'antriandokter.polieks', 'antriandokter.sukses', 'master_poli_bpjs.nama_poli');
        # 1 Join
        $query = $query
            ->leftJoin('dokter', function ($dokter) {
                $dokter->on('antriandokter.dokter_id', 'dokter.dokter_id');
            })
            ->leftJoin('master_poli', function ($poli) {
                $poli->on('antriandokter.poli_id', 'master_poli.poli_id');
            })
            ->leftJoin('master_poli_bpjs', function ($bpjs) {
                $bpjs->on('antriandokter.polibpjs', 'master_poli_bpjs.kode_poli');
            });
        # 2 Condition wajib
        $query = $query->where('antriandokter.no_hp', $this->telpon);

        # 3 FIlter
        # checkin = Y
        if ($this->checkin == 'Y') {
            $query = $query->where('checkin',  $this->checkin);
        }
        $query = $query->orderBy('antriandokter.tanggal', 'DESC')->get();

        return $query;
    }
}
