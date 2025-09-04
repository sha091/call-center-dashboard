<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Queue extends Model
{
    use HasFactory;
    protected $table = 'cc_queue';
    protected $primaryKey = 'queue_id';
    protected $fillable = [        
        'cc_id',
        'admin_id',
        'queue_name',
        'queue_type',
        'queue_status',        
    ];
}
