<?php

namespace App\Http\Controllers;

use App\Repositories\Auth\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class AuthController extends Controller
{

    private $auth_repo;
    private $param_array = [];
    public function __construct(
        AuthInterface $authInterface
    ) {
        $this->auth_repo = $authInterface;
    }

    public function signUp(Request $request)
    {
        try {
            # harus diisi semuanya
            if (empty($request->id) || empty($request->nama) || empty($request->telpon)) {
                $result = [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'ID, Nama dan Telepon Harus Diisi Semuanya'
                ];
                return response()->json($result);
            } else {
                $param_array['id'] = strval($request->id);
                $param_array['nama'] = $request->nama;
                $param_array['telpon'] = $request->telpon;

                $data = $this->auth_repo->signUp($param_array);

                if ($data == true) {
                    $result = [
                        "code" => Response::HTTP_NOT_FOUND,
                        "message" => "Nomor Kontak Sudah terdaftar"
                    ];
                } else {
                    $result = [
                        "code" => Response::HTTP_OK,
                        "message" => "Data Berhasil Di Input",
                        "data" => $param_array
                    ];
                }
                return response()->json($result);
            }
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan' . $e
            ];
            return response()->json($result);
        }
    }
    public function login(Request $request)
    {
        try {
            # harus diisi semuanya
            if (empty($request->id) || empty($request->telpon)) {
                $result = [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'ID dan Telepon Harus Diisi Semuanya'
                ];
                return response()->json($result);
            } else {
                $param_array['id'] = strval($request->id);
                $param_array['telpon'] = $request->telpon;

                $data = $this->auth_repo->login($param_array);

                if ($data['status'] == true) {
                    $result = [
                        "code" => Response::HTTP_OK,
                        "message" => "Berhasil",
                        "data" => $data['data']
                    ];
                } else {
                    $result = [
                        "code" => Response::HTTP_NOT_FOUND,
                        "message" => "Data Tidak Ditemukan"
                    ];
                }
                return response()->json($result);
            }
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan' . $e
            ];
            return response()->json($result);
        }
    }

    public function editProfil(Request $request)
    {
        try {
            # harus diisi semuanya
            if (empty($request->id) || empty($request->nama) || empty($request->telpon)) {
                $result = [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => 'ID, Nama dan Telepon Harus Diisi Semuanya'
                ];
                return response()->json($result);
            } else {
                $param_array['id'] = strval($request->id);
                $param_array['nama'] = $request->nama;
                $param_array['telpon'] = $request->telpon;


                $data = $this->auth_repo->editProfil($param_array);

                if ($data == true) {
                    $result = [
                        "code" => Response::HTTP_OK,
                        "message" => "Data Berhasil Di Update",
                    ];
                } else {
                    $result = [
                        "code" => Response::HTTP_NOT_FOUND,
                        "message" => "Data tidak Ditemukan"
                    ];
                }
                return response()->json($result);
            }
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan' . $e
            ];
            return response()->json($result);
        }
    }
}
