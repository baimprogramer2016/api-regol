<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\SignatureBPJS;
use Throwable;

class BpjsTesController extends Controller
{
    use SignatureBPJS;
    public function index(Request $request)
    {
        return $this->SignatureAntrol();
    }

    public function tesApi(Request $request)
    {
        try {
            $param['url'] = env('base_url_bpjs') . config('base_url.url-bpjs.diagnosa');

            // return $param['url'];

            $resultApi = json_decode($this->getDataBpjs2($param));

            return $resultApi;
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan ' . $e->getMessage()
            ];
            return response()->json($result);
        }
    }
}
