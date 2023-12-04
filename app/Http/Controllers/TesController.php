<?php

namespace App\Http\Controllers;

use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TesController extends Controller
{
    public function index()
    {
        $data = Tes::whereIn('nama', ['andi', 'ari'])->orderBy('medrec_no', 'ASC')->get()->take(1);
        return response()->json(['data' => $data]);
    }
}
