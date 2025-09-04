<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Queue_Stats extends Model
{
    use HasFactory;
    protected $table = 'cc_queue_stats';
    protected $primaryKey = 'id';

    public function admin(){
        return $this->belongsTo(CC_Admin::class,'staff_id','admin_id');
    }
}
