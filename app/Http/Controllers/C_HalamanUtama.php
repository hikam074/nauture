<?php

namespace App\Http\Controllers;

use App\Models\M_Katalog;
use App\Models\M_Lelang;
use App\Models\User;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_HalamanUtama extends Controller
{
    // public function showHalamanUtama(int $id, string $role)

    // !!!WARNING
    public function showHalamanUtama(Request $request)
    // WARNING!!!
    {
        // ambil hanya 10 data katalog
        $katalogs = M_Katalog::inRandomOrder()->take(10)->get();
        // ambil hanya 10 data lelang
        $lelangs = M_Lelang::where('tanggal_ditutup', '>=', Carbon::now()) // Hanya yang selesai
            ->inRandomOrder() // Acak
            ->take(10) // Ambil 10 data
            ->get();

        // periksa apakah user sudah login
        if (Auth::check()) {
            // $user = Auth::user();
            $user = User::where('id', $request['id'])->first();
            return view('V_HalamanUtama', compact('user', 'katalogs', 'lelangs'));
        }

        // mode guest
        return view('V_HalamanUtama', ['user' => null, 'katalogs' => $katalogs, 'lelangs' => $lelangs]);
    }
}
