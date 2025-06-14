<?php

namespace App\Http\Controllers;

use App\Models\M_PasangLelang;
use App\Models\M_Saldo;
use App\Models\M_StatusTransaksi;
use App\Models\M_Transaksi;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        if ($userRole === 'customer')
        {
            // Mengurutkan berdasarkan waktu pembayaran paling lama
            $userId = Auth::id();
            $transaksis = M_Transaksi::whereHas('lelang.pemenang', function ($query) use ($userId) {
                $query->whereHas('user', function ($subQuery) use ($userId) {
                    $subQuery->where('id', $userId); // Filter berdasarkan user yang login
                });
            })
            ->where('status_transaksi_id', $statusPending)
            ->whereNull('payment_time')
            ->orderBy('created_at', 'asc')
            ->get();

            // Mendapatkan pasang_lelang yang dimenangkan tetapi belum memiliki transaksi
            $pasangLelangs = M_PasangLelang::with('lelang')
                ->whereHas('lelang', function ($query) {
                    $query->where('pemenang_id', '!=', null); // Lelang sudah memiliki pemenang
                })
                ->whereDoesntHave('lelang.transaksi') // Belum memiliki transaksi terkait
                ->where('user_id', $userId)
                ->orderBy('created_at', 'asc')
                ->get();

            return $this->showDataLaporan($transaksis, $pasangLelangs);

        }
        elseif (($userRole === 'owner') || ($userRole === 'pegawai'))
        {
            // Tentukan periode 10 hari terakhir
            $startDate = Carbon::now()->subDays(9);
            $endDate = Carbon::now();
            // Buat koleksi tanggal dalam format Y-m-d
            $dates = collect(CarbonPeriod::create($startDate, $endDate)->toArray())
                ->map(fn ($date) => $date->format('Y-m-d'));
            // Ambil data transaksi, key by tanggal dengan format Y-m-d
            $dailyIncomeRaw = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->selectRaw('DATE(created_at) as date, SUM(gross_amount) as total')
                ->groupBy('date')
                ->get()
                ->keyBy(fn($item) => Carbon::parse($item->date)->format('Y-m-d'));
            // Gabungkan data transaksi dengan list tanggal agar selalu ada datanya
            $dailyIncome = $dates->map(fn ($date) => [
                'date' => $date,
                'label' => Carbon::parse($date)->translatedFormat('l, d F Y'),
                'total' => data_get($dailyIncomeRaw, "$date.total", 0),
            ]);

            // Log::info('Daily Income Raw:', $dailyIncomeRaw->toArray());
            // Log::info('Dates:', $dates->toArray());
            // Log::info('Daily Income:', $dailyIncome->toArray());


            // Tentukan 5 minggu terakhir termasuk minggu ini
            $startDate = Carbon::now()->startOfWeek()->subWeeks(4);
            $weeks = collect(range(0, 4))->map(function ($week) use ($startDate) {
                $weekDate = $startDate->copy()->addWeeks($week);
                return [
                    'week' => $weekDate->isoWeek(),
                    'year' => $weekDate->year,
                ];
            });
            // Ambil data dari database
            $weeklyIncomeRaw = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->selectRaw('YEAR(created_at) as year, WEEK(created_at, 1) as week, SUM(gross_amount) as total')
                ->groupBy('year', 'week')
                ->get()
                ->keyBy(fn($item) => "{$item->year}-{$item->week}");
            // Gabungkan data dengan default 0 untuk minggu tanpa transaksi
            $weeklyIncome = $weeks->map(function ($week) use ($weeklyIncomeRaw) {
                $weekStart = Carbon::now()->setISODate($week['year'], $week['week'])->startOfWeek();
                $monthStart = $weekStart->copy()->startOfMonth();
                $weekOfMonth = intval($monthStart->diffInWeeks($weekStart)) + 1;
                $monthLabel = $weekStart->translatedFormat('F');
                $weekKey = "{$week['year']}-{$week['week']}";
                return [
                    'year' => $week['year'],
                    'week' => $week['week'],
                    'week_label' => "Minggu $weekOfMonth $monthLabel",
                    'total' => data_get($weeklyIncomeRaw, "$weekKey.total", 0),
                ];
            });

            // Log::info('Weekly Income Raw:', $weeklyIncomeRaw->toArray());
            // Log::info('Weekly Income:', $weeklyIncome->toArray());

            // Tentukan 12 bulan terakhir
            $startMonth = Carbon::now()->startOfMonth()->subMonths(11);
            $months = collect(range(0, 11))->map(function ($month) use ($startMonth) {
                $monthDate = $startMonth->copy()->addMonths($month);
                return [
                    'month' => $monthDate->month,
                    'year' => $monthDate->year,
                    'month_label' => $monthDate->format('F'),
                ];
            });
            // Ambil data dari database
            $monthlyIncomeRaw = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(gross_amount) as total')
                ->groupBy('month', 'year')
                ->get()
                ->keyBy(fn ($item) => "{$item->year}-{$item->month}");
            // Gabungkan data dengan default 0
            $monthlyIncome = $months->map(fn ($month) => [
                'year' => $month['year'],
                'month' => $month['month'],
                'month_label' => $month['month_label'],
                'total' => data_get($monthlyIncomeRaw, "{$month['year']}-{$month['month']}.total", 0),
            ]);

            // Tentukan 3 tahun terakhir
            $startYear = Carbon::now()->subYears(2)->year;
            $years = collect(range($startYear, Carbon::now()->year));
            // Ambil data dari database
            $yearlyIncomeRaw = M_Transaksi::whereIn('status_transaksi_id', $statusTransaksiId)
                ->selectRaw('YEAR(created_at) as year, SUM(gross_amount) as total')
                ->groupBy('year')
                ->get()
                ->keyBy('year');
            // Gabungkan data dengan default 0
            $yearlyIncome = $years->map(fn ($year) => [
                'year' => $year,
                'total' => data_get($yearlyIncomeRaw, "$year.total", 0),
            ]);

            if ($userRole === 'pegawai')
            {
                // Mengurutkan berdasarkan waktu pembayaran paling lama
                $transaksis = M_Transaksi::with(['lelang.pemenang.user'])
                    ->where('status_transaksi_id', $statusSettlement)
                    ->whereNotNull('payment_time')
                    ->orderBy('payment_time', 'asc')
                    ->limit(10)
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

                return $this->showDataLaporan($transaksis, $pasangLelangs = null, $saldo, $incomeMingguIni, $incomeBulanIni, $weeklyIncome, $monthlyIncome, $yearlyIncome, $dailyIncome);

            }

            elseif($userRole === 'owner')
            {
                // Mengurutkan berdasarkan waktu pembayaran paling baru
                $transaksis = M_Transaksi::with(['lelang.pemenang.user'])
                    ->whereIn('status_transaksi_id', $statusTransaksiId)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
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

                return $this->showDataLaporan($transaksis, $pasangLelangs = null, $saldo, $incomeMingguIni, $incomeBulanIni, $weeklyIncome, $monthlyIncome, $yearlyIncome, $dailyIncome);
            }
        }
        else
        {
            abort(403, 'Unauthorized action [C_Profil::getDataLaporan]');
        }
    }

    public function showDataLaporan($transaksis, $pasangLelangs = null, $saldo = 0, $incomeMingguIni = 0, $incomeBulanIni = 0, $weeklyIncome = null, $monthlyIncome = null, $yearlyIncome = null, $dailyIncome = null)
    {
        return view('dashboard.d-dashboard.V_HalamanLaporan', compact('transaksis', 'pasangLelangs', 'saldo', 'incomeMingguIni', 'incomeBulanIni', 'weeklyIncome', 'monthlyIncome', 'yearlyIncome', 'dailyIncome'));
    }
}
