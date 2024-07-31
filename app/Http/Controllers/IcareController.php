<?php

namespace App\Http\Controllers;


use App\Repositories\Icare\IcareInterface;
use Illuminate\Http\Request;
use App\Traits\SignatureBPJS;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Throwable;
use GuzzleHttp\Client;

class IcareController extends Controller
{
    use SignatureBPJS;

    public $icare_repo;
    public $poli;
    public $tanggal;
    public function __construct(
        IcareInterface $icareInterface
    ) {

        $this->icare_repo = $icareInterface;
    }
    public function index(Request $request)
    {

        try {

            $this->icare_repo->deleteIcareNull();
            $process_data =  $this->icare_repo->processIcare();
            $this->icare_repo->deleteIcareDouble();
            $param['url'] = env('base_url_bpjs') . '/wsihs/api/rs/validate';


            if ($process_data[0]->response == "success") {

                $data_icare = $this->icare_repo->getIcare();


                foreach ($data_icare as $item_icare) {
                    $param['id'] = $item_icare->ID;

                    $param['payload'] = [
                        "param" => $item_icare->kode_peserta,
                        "kodedokter" => intval($item_icare->kode_dokter)
                    ];


                    $response_api = $this->sendIcare($param); //ada di signatureBpjs

                    $message = $response_api['code'] == '200' ? $response_api['response']->url : $response_api['message'];

                    $this->icare_repo->updateIcare($item_icare->ID, $message);
                    sleep(2);
                }

                $result = [
                    'code' => Response::HTTP_OK,
                    'data' => 'Selesai'
                ];
                return response()->json($result);
            }
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan ' . $e->getMessage()
            ];
            return response()->json($result);
        }
    }
}
