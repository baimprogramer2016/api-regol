<?php

namespace App\Http\Controllers;

use App\Repositories\Dokter\DokterInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class DokterController extends Controller
{
    private $dokter_repo;
    private $dokter_id = null;
    private $param_array = [];

    public function __construct(
        DokterInterface $dokterInterface
    ) {
        $this->dokter_repo = $dokterInterface;
    }

    # menampilkan keseluruhan dokter dengan kondisi
    public function getDokter(Request $request)
    {
        try {
            # jika dokter_id ada
            if ($request->exists('dokter_id')) {
                $this->dokter_id = $request->dokter_id;
            }

            $data = $this->dokter_repo->getDokter($this->dokter_id);

            if ($data->isEmpty()) {
                $result = [
                    "code" => Response::HTTP_NOT_FOUND,
                    "message" => "Data Tidak Ditemukan"
                ];
            } else {
                $result = [
                    "code" => Response::HTTP_OK,
                    "data" => $data
                ];
            }
            return response()->json($result);
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan'
            ];
            return response()->json($result);
        }
    }

    public function getJadwalDokter(Request $request)
    {
        try {
            # jika dokter_id ada
            if ($request->exists('dokter_id')) {
                $this->param_array['dokter_id'] = $request->dokter_id;
            }
            if ($request->exists('poli_id')) {
                $this->param_array['poli_id'] = $request->poli_id;
            }
            if ($request->exists('nama_dokter')) {
                $this->param_array['nama_dokter'] = $request->nama_dokter;
            }

            $data = $this->dokter_repo->getJadwalDokter($this->param_array);

            if ($data->isEmpty()) {
                $result = [
                    "code" => Response::HTTP_NOT_FOUND,
                    "message" => "Data Tidak Ditemukan"
                ];
            } else {
                $result = [
                    "code" => Response::HTTP_OK,
                    "data" => $data
                ];
            }
            return response()->json($result);
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan '.$e
            ];
            return response()->json($result);
        }
    }
}
