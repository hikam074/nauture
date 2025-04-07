<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'lelang_id',
        'title_notif',
        'body_notif',
        'link_click_action',
        'image_notif',
    ];

    // reference this lelang_id ke lelangs id
    public function lelang()
    {
        return $this->belongsTo(M_Lelang::class);
    }
}
