<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Notifiables extends Model
{
    use HasFactory;
    protected $table = 'cc_notifiables';
    protected $fillable = [
        'notification_id',
        'is_seen',
        'seen_at',
        'status',
        'reciever_id',
        'cc_id'
    ];

    public function notification()
    {
        return $this->belongsTo(CC_Notification::class, 'notification_id');
    }

    public function receiver()
    {
        return $this->belongsTo(CC_Admin::class, 'admin_id');
    }

}
