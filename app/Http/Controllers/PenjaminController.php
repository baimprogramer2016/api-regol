<?php

namespace App\Http\Controllers;

use App\Repositories\Penjamin\PenjaminInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class PenjaminController extends Controller
{

    private $penjamin_repo;
    private $param_array = [];

    public function __construct(
        PenjaminInterface $penjaminInterface
    ) {
        $this->penjamin_repo = $penjaminInterface;
    }


    public function getPenjamin(Request $request)
    {
        try {

            # jika dokter_id ada
            if ($request->exists('kode_eselon')) {
                $this->param_array['kode_eselon'] = $request->kode_eselon;
            }
            if ($request->exists('deskripsi')) {
                $this->param_array['deskripsi'] = $request->deskripsi;
            }

            $data = $this->penjamin_repo->getPenjamin($this->param_array);

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
                'message' => 'Terjadi Kesalahan ' . $e
            ];
            return response()->json($result);
        }
    }
}
