<?php

namespace App\Http\Controllers;

use App\Models\M_Notifikasi;
use Illuminate\Http\Request;
use App\Services\OneSignalService;

class C_Notifikasi
{
    public function getDataNotifikasi() {
        $notifs = M_Notifikasi::all();
        return $this->showHalamanNotifikasi($notifs);
    }

    public function showHalamanNotifikasi($notifs)
    {
        return view('dashboard.notifikasi', compact('notifs'));
    }



    public function sendNotification($lelangId, $judul, $isiPesan, $url = null)
    {
        if ($url == null) {$url = config('onesignal.this_app_url');}

        $oneSignal = new OneSignalService();
        $response = $oneSignal->sendNotification(
            $judul, // Judul
            $isiPesan, // pesan
            $url // Opsional, URL tujuan.
        );
        if (isset($response['error'])) {
            return response()->json(['status' => 'failed', 'message' => $response['error']], 500);
        }
        M_Notifikasi::create([
            'lelang_id' => $lelangId,
            'title_notif' => $judul,
            'body_notif' => $isiPesan,
            'link_click_action' =>$url,
        ]);
        return response()->json(['status' => 'success', 'data' => $response]);
    }
}


