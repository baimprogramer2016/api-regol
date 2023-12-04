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
        $query = $query->select('antriandokter_test.tanggal', 'antriandokter_test.dokter_id', 'dokter.nama_dokter', 'antriandokter_test.antrian', 'master_poli.poli_name', 'antriandokter_test.nomr', 'antriandokter_test.nama', 'antriandokter_test.tgl_lahir', 'antriandokter_test.diregister', 'antriandokter_test.t_call', 'antriandokter_test.kode', 'antriandokter_test.no_antri', 'antriandokter_test.t_reg', 'antriandokter_test.waktu', 'antriandokter_test.bayar', 'antriandokter_test.no_urut_poli', 'antriandokter_test.printdok', 'antriandokter_test.update_by', 'antriandokter_test.tracer', 'antriandokter_test.qrcode', 'antriandokter_test.verifikasi', 'antriandokter_test.keterangan', 'antriandokter_test.no_hp', 'antriandokter_test.alamat', 'antriandokter_test.tgl_online', 'antriandokter_test.checkin', 'antriandokter_test.checkin_waktu', 'antriandokter_test.tanggal2', 'antriandokter_test.terlayani', 'antriandokter_test.lastupdate', 'antriandokter_test.nomorkartu', 'antriandokter_test.nik', 'antriandokter_test.nomorref', 'antriandokter_test.jenisref', 'antriandokter_test.jenisreq', 'antriandokter_test.polieks', 'antriandokter_test.sukses', 'master_poli_bpjs.nama_poli');
        # 1 Join
        $query = $query
            ->leftJoin('dokter', function ($dokter) {
                $dokter->on('antriandokter_test.dokter_id', 'dokter.dokter_id');
            })
            ->leftJoin('master_poli', function ($poli) {
                $poli->on('antriandokter_test.poli_id', 'master_poli.poli_id');
            })
            ->leftJoin('master_poli_bpjs', function ($bpjs) {
                $bpjs->on('antriandokter_test.polibpjs', 'master_poli_bpjs.kode_poli');
            });
        # 2 Condition wajib
        $query = $query->where('antriandokter_test.no_hp', $this->telpon);

        # 3 FIlter
        # checkin = Y
        if ($this->checkin == 'Y') {
            $query = $query->where('checkin',  $this->checkin);
        }
        $query = $query->orderBy('antriandokter_test.tanggal', 'DESC')->get();

        return $query;
    }
}
