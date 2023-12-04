<?php

namespace App\Http\Controllers;

use App\Repositories\Tanggal\TanggalInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class TanggalController extends Controller
{

    private $tanggal_repo;
    private $tanggal = null;

    public function __construct(
        TanggalInterface $tanggalInterface
    ) {
        $this->tanggal_repo = $tanggalInterface;
    }

    # menampilkan keseluruhan dokter dengan kondisi
    public function getTanggalLibur(Request $request)
    {
        try {
            # jika poli_id ada
            if ($request->exists('tanggal')) {
                $this->tanggal = $request->tanggal;
            }

            $data = $this->tanggal_repo->getTanggalLibur($this->tanggal);

            if ($data->isEmpty()) {
                $result = [
                    "code" => Response::HTTP_NOT_FOUND,
                    "status" => false,
                    "message" => "Data Tidak Ditemukan"
                ];
            } else {
                $result = [
                    "code" => Response::HTTP_OK,
                    "status" => true,
                    "data" => $data
                ];
            }
            return response()->json($result);
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'status' => false,
                'message' => 'Terjadi Kesalahan'
            ];
            return response()->json($result);
        }
    }
}
