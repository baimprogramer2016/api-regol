<?php

namespace App\Repositories\Dokter;

use Illuminate\Http\Response;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Poli;

class DokterRepository implements DokterInterface
{

    private $dokter_model, $jadwal_dokter_model;

    private $dokter_id, $poli_id, $nama_dokter = null;

    public function __construct(
        Dokter $dokter_model,
        JadwalDokter $jadwal_dokter_model,
    ) {
        $this->dokter_model = $dokter_model;
        $this->jadwal_dokter_model = $jadwal_dokter_model;
    }

    public function getDokter($dokter_id)
    {
        return $this->dokter_model
            ->where('nama_dokter', '<>', '-')
            ->where('aktif', 'Y')
            ->whereNotIn('bidang_keahlian', ['Fis', 'Lab', 'NM', 'BS', 'Prw', 'Dr.IGD', 'Gz'])
            ->when($dokter_id != null, function ($q) use ($dokter_id) {
                return $q->where('dokter_id', $dokter_id);
            })->get();
    }

    public function getJadwalDokter($param_array)
    {
        if (array_key_exists('dokter_id', $param_array)) {
            $this->dokter_id = $param_array['dokter_id'];
        }
        if (array_key_exists('poli_id', $param_array)) {
            $this->poli_id = $param_array['poli_id'];
        }
        if (array_key_exists('nama_dokter', $param_array)) {
            $this->nama_dokter = $param_array['nama_dokter'];
        }

        $query = $this->jadwal_dokter_model->query();
        # 0 Kolom
        $query = $query->select('jadwal_dokter.dokter_id', 'dokter.kodebpjs', 'dokter.nama_dokter', 'master_poli.poli_name', 'jadwal_dokter.senin', 'jadwal_dokter.selasa', 'jadwal_dokter.rabu', 'jadwal_dokter.kamis', 'jadwal_dokter.jumat', 'jadwal_dokter.sabtu', 'jadwal_dokter.kamar', 'jadwal_dokter.keterangan', 'jadwal_dokter.praktek', 'jadwal_dokter.pic_dr', 'jadwal_dokter.fotodr', 'jadwal_dokter.fotoada', 'jadwal_dokter.kuota', 'jadwal_dokter.terambil', 'jadwal_dokter.poli_id', 'jadwal_dokter.headerantri', 'jadwal_dokter.viewjadwal', 'jadwal_dokter.tgl_update', 'jadwal_dokter.senin1', 'jadwal_dokter.selasa1', 'jadwal_dokter.rabu1', 'jadwal_dokter.kamis1', 'jadwal_dokter.jumat1', 'jadwal_dokter.sabtu1', 'jadwal_dokter.urutan', 'jadwal_dokter.no_siang', 'jadwal_dokter.no_sore', 'jadwal_dokter.flags', 'jadwal_dokter.praktekP', 'jadwal_dokter.praktekS', 'jadwal_dokter.praktekR', 'jadwal_dokter.tgl_server', 'jadwal_dokter.update_by', 'jadwal_dokter.loket_lgsg', 'jadwal_dokter.loket_lgsg2', 'jadwal_dokter.minggu', 'jadwal_dokter.minggu1');
        # 1 Join
        $query = $query
            ->leftJoin('dokter', function ($dokter) {
                $dokter->on('jadwal_dokter.dokter_id', 'dokter.dokter_id');
            })
            ->leftJoin('master_poli', function ($poli) {
                $poli->on('jadwal_dokter.poli_id', 'master_poli.poli_id');
            });
        # 2 Condition Standart
        $query = $query->where('jadwal_dokter.dokter_id', '<>', '')
            ->where('jadwal_dokter.poliname', '<>', '-')
            ->where('jadwal_dokter.poli_id', '<>', 'Y')
            ->where('dokter.aktif', 'Y');

        # 3 FIlter
        $query = $query
            ->when($this->poli_id != null, function ($q) {
                return $q->where('jadwal_dokter.poli_id', $this->poli_id);
            })
            ->when($this->dokter_id != null, function ($q) {
                return $q->where('jadwal_dokter.dokter_id', $this->dokter_id);
            })
            ->when($this->nama_dokter != null, function ($q) {
                return $q->where('dokter.nama_dokter', 'like', '%' . $this->nama_dokter . '%');
            })->get();

        return $query;
    }
}
