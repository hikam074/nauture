<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_XenditPayout extends Model
{
    protected $table = 'xendit_payouts';

    protected $fillable = [
        'xendit_id',
        'pengajuan_dana_id',
        'amount',
        'payout_at',
        'expiry_date',
        'status_transaksi_id',
        'bank_code',
        'account_number',
        'account_holder_name',
        'raw_response',
    ];

    // reference this pengajuan_dana_id ke pengajuan_danas id
    public function pengajuanDana()
    {
        return $this->belongsTo(M_PengajuanDana::class);
    }
    // reference this status_transaksi_id ke status_transaksis id
    public function statusTransaksi()
    {
        return $this->belongsTo(M_StatusTransaksi::class);
    }
}
