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
    public $tgl_awal, $tgl_akhir, $tgl_kunjungan;
    public $bulan, $tahun, $no_kartu;
    public $filter = 1;
    public function __construct(
        AntrolInterface $antrolInterface
    ) {

        $this->antrol_repo = $antrolInterface;
        $this->tgl_awal = Carbon::now()->format('Y-m-d');
        $this->tgl_akhir = Carbon::now()->format('Y-m-d');
        $this->tgl_kunjungan = Carbon::now()->format('Y-m-d');
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

    public function cariSep(Request $request)
    {
        try {

            $this->tgl_awal = Carbon::now()->startOfMonth()->format('Y-m-d');
            $this->tgl_akhir = Carbon::now()->endOfMonth()->format('Y-m-d');

            # jika parameter di isi, jika tidak maka default
            if ($request->exists('tgl_awal')) {
                $this->tgl_awal = $request->tgl_awal;
            }
            if ($request->exists('tgl_akhir')) {
                $this->tgl_akhir = $request->tgl_akhir;
            }
            if ($request->exists('tgl_kunjungan')) {
                $this->tgl_kunjungan = $request->tgl_kunjungan;
            }

            //jika ada kartu
            if ($request->exists('no_kartu')) {
                if (empty($request->no_kartu)) {
                    $result = [
                        'code' => Response::HTTP_BAD_REQUEST,
                        'message' => "No Kartu BPJS Harus di isi",
                    ];
                    return response()->json($result);
                } else {

                    # cari berdasarkan no kartu yang diinput
                    $this->no_kartu = $request->no_kartu;
                    $result = [
                        'code' => Response::HTTP_OK,
                        'message' => "Pake Kartu Belum tersedia",
                    ];
                    return response()->json($result);
                }
            } else {

                # cari berdasarkan query
                $this->antrol_repo->truncateTempSep();
                //query no_bpjs pasien join dengan reg dimana sep masih kosong

                $data_pasien =  $this->antrol_repo->getSepReady($this->tgl_kunjungan);
                // return $data_pasien;
                foreach ($data_pasien as $item_pasien) {

                    $param['url'] = env('base_url_bpjs') . '/vclaim-rest/monitoring/HistoriPelayanan/Nokartu/' . $item_pasien->no_peserta . '/tglMulai/' . $this->tgl_awal . '/tglAkhir/' . $this->tgl_akhir;
                    $data_response = $this->getDataBpjs($param);
                    if (!empty($data_response)) {
                        $resultApi = json_decode($data_response, true);
                        foreach ($resultApi['histori'] as $item_sep) {
                            $this->antrol_repo->insertSep($item_sep);
                        }
                    }
                }
                # delete yang diluar tanggal kunjungan
                $this->antrol_repo->deleteNotNow($this->tgl_kunjungan);

                // # update sep
                $this->antrol_repo->updateSep();

                $result = [
                    'code' => Response::HTTP_OK,
                    'message' => 'Sukses'
                ];

                return response()->json($result);
            }

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
