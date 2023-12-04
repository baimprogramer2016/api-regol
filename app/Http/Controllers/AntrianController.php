<?php

namespace App\Http\Controllers;

use App\Repositories\Antrian\AntrianInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class AntrianController extends Controller
{
    private $antrian_repo;
    private $param_array = [];

    public function __construct(
        AntrianInterface $antrianInterface
    ) {
        $this->antrian_repo = $antrianInterface;
    }

    public function getAntrian(Request $request)
    {
        try {
            #filter semua
            if (empty($request->checkin) and empty($request->terlayani) and empty($request->telpon)) {
                $result = [
                    "code" => Response::HTTP_NOT_FOUND,
                    "message" => "Form Telepon , Checkin dan Terlayani Harus diisi"
                ];
                return response()->json($result);
            }
            $this->param_array['checkin'] = $request->checkin;
            $this->param_array['terlayani'] = $request->terlayani;
            $this->param_array['telpon'] = $request->telpon;

            $data = $this->antrian_repo->getAntrian($this->param_array);

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
                'message' => 'Terjadi Kesalahan '
            ];
            return response()->json($result);
        }
    }
}
