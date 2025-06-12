<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class C_Whatsapp extends Controller
{
    private function formatRupiah(int $angka): string
    {
        return 'Rp. ' . number_format($angka, 0, ',', '.');
    }

    private function formatMessage($nama_template = null, $params = [])
    {
        $templates = [
            'pemenang_lelang' =>
            "🎉 *[ANDA MEMENANGKAN LELANG!]* 🎉\n" .
                "Selamat! Anda memenangkan lelang *{nama_produk_lelang}* di *NauTure* sebagai penawar tertinggi! 🏆\n\n" .
                "📋 *Detail Pemenang*:\n" .
                "> 🧑‍💼 *Nama Anda* : {name}\n" .
                "> 🆔 *Kode Lelang* : {kode_lelang}\n" .
                "> 💰 *Tawaran Anda* : {bid}\n" .
                "> ⏳ *Tenggat Pelunasan* : {deadline}\n" .
                "> 🔗 *Link Lelang* : {url}\n\n" .
                "⚠️ *Mohon segera melunasi pembayaran Anda sebelum jatuh tempo*.\n" .
                "Jika *melebihi batas waktu*, maka lelang akan *dialihkan* ke pemenang lain dan *akun Anda akan diberi penalti*. ❗\n\n" .
                "Terima kasih telah berlelang bersama *NauTure*! 🌱\n" .
                "> Ini adalah pesan mode percobaan, semua kontan diatas adalah fiktif dan untuk keperluan pengembangan!\n\n" .
                "🌐 *~NauTure Developer*",
        ];

        // Pilih template
        if ($nama_template && isset($templates[$nama_template])) {
            $message = $templates[$nama_template];

            // Ganti placeholder dengan nilai aktual
            foreach ($params as $key => $value) {
                if ($key === 'bid') {
                    $value = $this->formatRupiah((int)$value); // Format bid ke rupiah
                }
                $message = str_replace("{" . $key . "}", $value, $message);
            }

            return $message;
        }

        // Jika tidak ada template, kembalikan pesan kosong
        return null;
    }


    public function sendMessage(Request $request)
    {
        // Data pesan dari request
        $target = $request->input('target'); // Nomor tujuan
        $message = $request->input('message'); // Isi pesan
        $delay = $request->input('delay', 0); // Waktu tunda pengiriman (opsional)

        $template = $request->input('template'); // Nama template (opsional)
        $params = $request->input('params', []); // Parameter untuk template (opsional)

        if ($template) {
            $message = $this->formatMessage($template, $params);
        }

        // API Key dari .env
        $apiKey = env('FONNTE_API_KEY');

        // Kirim permintaan ke API Fonnte
        $response = Http::withHeaders([
            'Authorization' => $apiKey,
            'Content-Type'  => 'application/json',
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
            'delay' => $delay,
        ]);

        // Cek hasil pengiriman
        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim!',
                'data' => $response->json(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim pesan!',
            'error' => $response->body(),
        ], 500);
    }
}
