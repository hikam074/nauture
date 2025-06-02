<?php

namespace App\Http\Controllers;

use App\Models\M_City;
use Illuminate\Http\Request;

class C_Alamat extends Controller
{
    public function getDataCity($provinsiId)
    {
        $cities = M_City::where('provinsi_id', $provinsiId)->get();
        return response()->json($cities);
    }
}
