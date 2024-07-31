<?php

namespace App\Repositories\Icare;

use Illuminate\Http\Response;
use App\Models\Icare;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IcareRepository implements IcareInterface
{

    private $icare_model;

    public function __construct(
        Icare $icare_model,

    ) {

        $this->icare_model = $icare_model;
    }

    public function processIcare()
    {
        return DB::connection('odbc')->select('CALL proc_create_icare_bpjs');
    }

    public function getIcare()
    {

        return DB::connection('odbc')->select('SELECT TOP 10 * FROM icare_bpjs WHERE response IS NULL AND CONVERT(DATE,tanggal_kirim) = CONVERT(DATE,GETDATE())');
    }
    public function deleteIcareDouble()
    {
        return DB::connection('odbc')->select('CALL proc_delete_icare_double');
    }

    public function deleteIcareNull()
    {
        return $this->icare_model->whereNull('response')->orWhere('response', 'LIKE', 'Anda dapat%')->delete();
    }

    //skdp
    public function updateIcare($id, $response)
    {
        return $this->icare_model::where('ID', '=', $id)
            ->update([
                'response' => $response
            ]);
    }
}
