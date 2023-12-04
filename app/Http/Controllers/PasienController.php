<?php

namespace App\Http\Controllers;

use App\Repositories\Pasien\PasienInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class PasienController extends Controller
{

    private $pasien_repo;
    private $param_array = [];

    private $nomr = null;

    public function __construct(
        PasienInterface $pasienInterface
    ) {
        $this->pasien_repo = $pasienInterface;
    }

    public function procVerifikasi(Request $request)
    {
        try {
            # identifier request dan param berbeda, pada param merupakan nama dari field di table contoh 'medrec_no'
            if ($request->exists('mr')) {
                $this->param_array['medrec_no'] = $request->mr;
                if (empty($request->mr)) {
                    $result = [
                        "code" => Response::HTTP_BAD_REQUEST,
                        "message" => "Nomor MR Harus Diisi"
                    ];
                    return response()->json($result);
                }
            } elseif ($request->exists('ktp')) {
                $this->param_array['no_ktp'] = $request->ktp;
                if (empty($request->ktp)) {
                    $result = [
                        "code" => Response::HTTP_BAD_REQUEST,
                        "message" => "Nomor KTP Harus Diisi"
                    ];
                    return response()->json($result);
                }
            } elseif ($request->exists('bpjs')) {
                $this->param_array['no_peserta'] = $request->bpjs;
                if (empty($request->bpjs)) {
                    $result = [
                        "code" => Response::HTTP_BAD_REQUEST,
                        "message" => "Nomor BPJS Harus Diisi"
                    ];
                    return response()->json($result);
                }
            } else {
                # Salah satu harus ada ktp, mr, bpjs
                $result = [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => "Salah Satu harus di isi antara Nomor KTP/BPJS/MR"
                ];
                return response()->json($result);
            }

            # wajib ada tgl lahir, jika tidak ada langsung return
            if (empty($request->tgl_lahir)) {
                $result = [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => "Tanggal Lahir Wajib diisi"
                ];
                return response()->json($result);
            }
            $this->param_array['tgl_lahir'] = $request->tgl_lahir;

            # proses pengecekan
            $data = $this->pasien_repo->procVerifikasi($this->param_array);

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


    # menampilkan keseluruhan dokter dengan kondisi
    public function getPasienByMr(Request $request)
    {

        try {
            # jika poli_id ada
            if ($request->exists('nomr')) {
                if (empty($request->nomr)) {
                    $result = [
                        "code" => Response::HTTP_BAD_REQUEST,
                        "message" => "No. MR Harus di isi"
                    ];
                    return response()->json($result);
                }
                $this->nomr = $request->nomr;
            } else {

                $result = [
                    "code" => Response::HTTP_BAD_REQUEST,
                    "message" => "No. MR Harus di isi"
                ];
                return response()->json($result);
            }

            $data = $this->pasien_repo->getPasienByMr($this->nomr);

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
