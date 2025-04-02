<?php

namespace App\Http\Controllers;

use App\Models\M_Katalog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_Dashboard extends Controller
{
    public function index()
    {
        // ambil data katalog
        $katalogs = M_Katalog::all();

        // periksa apakah user sudah login
        if (Auth::check()) {
            $user = Auth::user();
            return view('dashboard', compact('user', 'katalogs'));
        }

        // mode guest
        return view('dashboard', ['user' => null, 'katalogs' => $katalogs]);
    }
}
