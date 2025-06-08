<?php

namespace App\Http\Controllers;

use App\Models\M_Saldo;
use App\Models\M_StatusTransaksi;
use App\Models\M_Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class C_Dashboard extends Controller
{
    public function getDataLaporan()
    {
        $userRole = Auth::user()->role->nama_role;
        $statusSettlement = M_StatusTransaksi::where('kode_status_transaksi', 'settlement')->first()->id;
        $statusPending = M_StatusTransaksi::where('kode_status_transaksi', 'pending')->first()->id;
        $statusTransaksiId = M_StatusTransaksi::whereIn('kode_status_transaksi', ['settlement', 'capture', 'delivering', 'delivered'])
            ->pluck('id')
            ->toArray();

        if ($userRole === 'customer') {
            // Mengurutkan berdasarkan waktu pembayaran paling lama
            $transaksis = M_Transaksi::with(['lelang.pemenang.user'])
                ->where('status_transaksi_id', $statusPending)
                ->whereNull('payment_time')
                ->orderBy('created_at', 'asc')
                ->get();

            $incomeBulanIni = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('gross_amount');

            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $incomeMingguIni = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('gross_amount');

            $saldo = M_Saldo::find(1);
            return $this->showDataLaporan($transaksis, $saldo, $incomeMingguIni, $incomeBulanIni);

        } elseif ($userRole === 'pegawai') {
            // Mengurutkan berdasarkan waktu pembayaran paling lama
            $transaksis = M_Transaksi::with(['lelang.pemenang.user'])
                ->where('status_transaksi_id', $statusSettlement)
                ->whereNotNull('payment_time')
                ->orderBy('payment_time', 'asc')
                ->get();

            $incomeBulanIni = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('gross_amount');

            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $incomeMingguIni = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('gross_amount');

            $saldo = M_Saldo::find(1);
            return $this->showDataLaporan($transaksis, $saldo, $incomeMingguIni, $incomeBulanIni);

        } elseif ($userRole === 'owner') {
            // Mengurutkan berdasarkan waktu pembayaran paling baru
            $transaksis = M_Transaksi::with(['lelang.pemenang.user'])
                ->whereIn('status_transaksi_id', $statusTransaksiId)
                ->orderBy('created_at', 'desc')
                ->get();

            $incomeBulanIni = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('gross_amount');

            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $incomeMingguIni = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('gross_amount');

            $saldo = M_Saldo::find(1);
            return $this->showDataLaporan($transaksis, $saldo, $incomeMingguIni, $incomeBulanIni);
        }

        else {
            abort(403, 'Unauthorized action [C_Profil::getDataLaporan]');
        }
    }

    public function showDataLaporan($transaksis, $saldo = 0, $incomeMingguIni = 0, $incomeBulanIni = 0)
    {
        return view('dashboard.d-dashboard.V_HalamanLaporan', compact('transaksis','saldo', 'incomeMingguIni', 'incomeBulanIni'));
    }
}
