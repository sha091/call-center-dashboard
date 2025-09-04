<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Crm_Activity extends Model
{
    use HasFactory;
    protected $table = 'cc_crm_activity';
    protected $fillable = [
        'staff_id',
        'start_datetime',
        'end_datetime',
        'status',
        'update_datetime',
        'cc_id'
    ];

}
