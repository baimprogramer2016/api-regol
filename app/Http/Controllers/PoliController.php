<?php

namespace App\Http\Controllers;

use App\Repositories\Poli\PoliInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class PoliController extends Controller
{
    private $poli_repo;
    private $poli_id = null;
    private $tipe_poli = null;

    public function __construct(
        PoliInterface $poliInterface
    ) {
        $this->poli_repo = $poliInterface;
    }

    # menampilkan keseluruhan dokter dengan kondisi
    public function getPoli(Request $request)
    {
        try {
            # jika poli_id ada
            if ($request->exists('poli_id')) {
                $this->poli_id = $request->poli_id;
            }

            $data = $this->poli_repo->getPoli($this->poli_id);

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

    public function getNamaUnit(Request $request)
    {
        try {
            # jika poli_id ada
            if ($request->exists('tipe_poli')) {
                $this->tipe_poli = $request->tipe_poli;
            }

            $data = $this->poli_repo->getNamaUnit($this->tipe_poli);

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
}
