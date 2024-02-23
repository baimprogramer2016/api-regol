<?php

namespace App\Repositories\Antrol;

interface AntrolInterface
{

    public function getPoli();

    // skdp
    public function insertSkdp($param = []);
    public function truncateTemp();
    public function updateSkdp();

    //sep

    public function getSepReady($tgl_kunjungan);
    public function insertSep($param = []);
    public function truncateTempSep();
    public function updateSep();

    public function deleteNotNow($tgl_kunjungan);
    public function truncateTempSepCasemix();
    public function getSepReadySepCasemix($tgl_kunjungan);
    public function insertSepCasemix($param = []);
    public function deleteNotNowCasemix($tgl_kunjungan);
    public function updateSepCasemix();
}
