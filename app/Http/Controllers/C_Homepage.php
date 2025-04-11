<?php

namespace App\Http\Controllers;

use App\Models\M_Katalog;
use App\Models\M_Lelang;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_Homepage extends Controller
{
    public function index()
    {
        // ambil hanya 5 data katalog
        $katalogs = M_Katalog::take(5)->get();
        // ambil hanya 5 data lelang
        $lelangs = M_Lelang::take(5)->get();

        // periksa apakah user sudah login
        if (Auth::check()) {
            $user = Auth::user();
            return view('homepage', compact('user', 'katalogs', 'lelangs'));
        }

        // mode guest
        return view('homepage', ['user' => null, 'katalogs' => $katalogs, 'lelangs' => $lelangs]);
    }
}
