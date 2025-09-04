<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Notification extends Model
{
    use HasFactory;
    protected $table = 'cc_notifications';
    protected $fillable = [
        'type',
        'text',
        'notifying_user_id',
        'status',
        'cc_id'
    ];

    public function receivers()
    {
        return $this->hasMany(CC_Notifiables::class, 'notification_id');
    }
}
