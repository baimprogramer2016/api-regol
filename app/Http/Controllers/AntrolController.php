<?php

namespace App\Http\Controllers;

use App\Repositories\Antrol\AntrolInterface;
use Illuminate\Http\Request;
use App\Traits\SignatureBPJS;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Throwable;
use GuzzleHttp\Client;

class AntrolController extends Controller
{

    use SignatureBPJS;

    public $antrol_repo;
    public $tgl_awal;
    public $tgl_akhir;
    public $filter = 1;
    public function __construct(
        AntrolInterface $antrolInterface
    ) {

        $this->antrol_repo = $antrolInterface;
        $this->tgl_awal = Carbon::now()->format('Y-m-d');
        $this->tgl_akhir = Carbon::now()->format('Y-m-d');
    }
    public function index(Request $request)
    {
        return $this->SignatureAntrol();
    }
    public function poli(Request $request)
    {
        try {
            $param['url'] = env('base_url_bpjs') . '/antreanrs/ref/poli';

            $resultApi = json_decode($this->getDataBpjs($param));

            $object = [];
            foreach ($resultApi as $item_poli) {
                array_push($object, [
                    "nama_poli" => $item_poli->nmpoli,
                    "nama_sub_poli" => $item_poli->nmsubspesialis
                ]);
            }
            return $object;
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan ' . $e->getMessage()
            ];
            return response()->json($result);
        }
    }

    public function udpateSkdp(Request $request, $filter)
    {
        try {

            $this->filter = $filter;
            //jika ada parameter
            if ($request->exists('tgl_awal')) {
                $this->tgl_awal = $request->tgl_awal;
            }
            if ($request->exists('tgl_akhir')) {
                $this->tgl_akhir = $request->tgl_akhir;
            }

            //jika filter adalah tanggal kontrol, maka untuk esok harinya
            if ($this->filter == 2) {
                $this->tgl_awal = date('Y-m-d', strtotime($this->tgl_awal . ' +1 day'));;
                $this->tgl_akhir = date('Y-m-d', strtotime($this->tgl_akhir . ' +1 day'));;
            }

            $param['url'] = env('base_url_bpjs') . '/vclaim-rest/RencanaKontrol/ListRencanaKontrol/tglAwal/' . $this->tgl_awal . '/tglAkhir/' . $this->tgl_akhir . '/filter/' . $this->filter;

            $resultApi = json_decode($this->getDataBpjs($param), true);

            # kosongkan dahulu
            $this->antrol_repo->truncateTemp();

            # isi data skdp
            foreach ($resultApi['list'] as $item_skdp) {
                $this->antrol_repo->insertSkdp($item_skdp);
            }

            # jalan kan sp update ke trans_lembar_control
            $this->antrol_repo->updateSkdp();

            $result = [
                'code' => Response::HTTP_OK,
                'message' => 'Sukses'
            ];
            return response()->json($result);
            // return $resultDecompres;
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan ' . $e->getMessage()
            ];
            return response()->json($result);
        }
    }
}
