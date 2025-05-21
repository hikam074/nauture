<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Midtrans\Config;
use Midtrans\Notification;

use App\Models\M_Transaksi;
use App\Models\M_StatusTransaksi;
use Exception;

class C_Midtrans extends Controller
{
    public function handleNotification(Request $request)
    {
        // Validasi payload
        if (empty($request->all())) {
            Log::error('Midtrans notification: Empty payload');
            return response()->json(['message' => 'Empty payload'], 400);
        }

        $payload = $request->all();
        Log::info('incoming-midtrans', ['payload' => $payload]);

        try
        {
            $orderId = $payload['order_id'] ?? null;
            $status = $payload['status_code'] ?? null;
            $grossAmount = $payload['gross_amount'] ?? null;

            if (!$orderId || !$status || !$grossAmount) {
                return response()->json(['message' => 'Invalid payload'], 400);
            }

            $signature = hash('sha512', $orderId.$status.$grossAmount.config('midtrans.serverKey'));

            if ($signature !== ($payload['signature_key'] ?? '')) {
                Log::error('Invalid signature key', [
                    'received' => $payload['signature_key'] ?? '',
                    'calculated' => $signature
                ]);
                return response()->json(['message' => 'Invalid signature'], 401);
            }

            $transactionStatus = $payload['transaction_status'];

            $order = M_Transaksi::where('order_id', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status transaksi berdasarkan status dari Midtrans
            switch ($transactionStatus) {
                case 'capture':
                    $order->payment_time = now();
                    $order->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'settlement')->first()->id;
                    break;
                case 'settlement':
                    $order->payment_time = $payload['settlement_time'];
                    $order->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'settlement')->first()->id;
                    break;
                case 'pending':
                    $order->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'pending')->first()->id;
                    break;
                case 'deny':
                    $order->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'deny')->first()->id;
                    break;
                case 'cancel':
                    $order->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'cancel')->first()->id;
                    break;
                case 'expire':
                    $order->status_transaksi_id = M_StatusTransaksi::where('kode_status_transaksi', 'failed')->first()->id;
                    break;
                default:
                    break;
            }

            $order->save();

            return response()->json(['message' => 'Transaction status updated successfully']);
        }
        catch (Exception $e) {
            Log::error('Error processing Midtrans notification: ' . $e->getMessage());
            return response()->json(['message' => 'errorr']);
        }
    }
}
