<?php


namespace App\Repositories\Tindakan;

interface TindakanInterface
{
    public function getTindakanPoli($search_poli, $search_tindakan);

    public function updateTIndakan($poli_id, $tindakan_id, $aktif);
}
