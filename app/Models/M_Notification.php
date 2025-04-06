<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'kode_lelang',
        'title_notif',
        'body_notif',
        'link_click_action',
        'image_notif',
    ];

    // reference this kode_lelang ke lelangs kode_lelang
    public function lelang()
    {
        return $this->belongsTo(M_Lelang::class, 'kode_lelang', 'kode_lelang');
    }
}
