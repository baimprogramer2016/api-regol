<?php

namespace App\Repositories\Antrol;

interface AntrolInterface
{

    public function getPoli();

    public function insertSkdp($param = []);
    public function truncateTemp();
    public function updateSkdp();
}
