<?php

namespace App\Http\Controllers;

use App\Repositories\Tindakan\TindakanInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class TindakanControl extends Controller
{

    private $tindakan_poli_model;

    private $search_poli = null;
    private $search_tindakan = null;

    public function __construct(
        TindakanInterface $tindakanInterface
    ) {
        $this->tindakan_poli_model = $tindakanInterface;
    }

    public function index(Request $request)
    {
        try {
            if ($request->exists('search_poli')) {
                $this->search_poli = $request->search_poli;
            }
            if ($request->exists('search_tindakan')) {
                $this->search_tindakan = $request->search_tindakan;
            }
            $data = $this->tindakan_poli_model->getTindakanPoli($this->search_poli, $this->search_tindakan);

            return view('pages.tindakan', [
                "data_tindakan" => $data
            ]);
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan ' . $e->getMessage()
            ];
            return response()->json($result);
        }
    }

    public function update(Request $request)
    {
        try {
            $poli_id = $request->poli_id;
            $tindakan_id = $request->tindakan_id;
            $aktif =  ($request->isChecked == 'true') ? 'Y' : 'N';
            $this->tindakan_poli_model->updateTIndakan($poli_id, $tindakan_id, $aktif);
            return 'Tindakan di Update';
        } catch (Throwable $e) {
            $result = [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Terjadi Kesalahan ' . $e->getMessage()
            ];
            return 'Terjadi Kesalahan ' . $e->getMessage();
        }
    }
}
