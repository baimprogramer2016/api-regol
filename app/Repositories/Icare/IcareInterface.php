<?php

namespace App\Repositories\Icare;

interface IcareInterface
{

    public function processIcare();
    public function getIcare();

    public function deleteIcareDouble();
    public function deleteIcareNull();
    // skdp
    public function updateIcare($id, $response);
}
