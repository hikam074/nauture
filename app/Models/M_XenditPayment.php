<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_XenditPayment extends Model
{
    protected $table = 'xendit_payments';

    protected $fillable = [
        'xendit_id',
        'transaksi_id',
        'amount',
        'paid_at',
        'expiry_date',
        'status_transaksi_id',
        'metode_pembayaran_id',
        'bank_code',
        'account_number',
        'qr_string',
        'raw_response',
    ];

    // reference this transaksi_id ke transaksis id
    public function transaksi()
    {
        return $this->belongsTo(M_Transaksi::class);
    }
    // reference this status_transaksi_id ke status_transaksis id
    public function statusTransaksi()
    {
        return $this->belongsTo(M_StatusTransaksi::class);
    }
    // reference this metode_pembayaran_id ke metode_pembayarans id
    public function metodePembayaran()
    {
        return $this->belongsTo(M_MetodePembayaran::class);
    }
}
