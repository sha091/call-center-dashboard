<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Campaign extends Model
{
    use HasFactory;
    protected $table = 'cc_campaign';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'cc_id',
        'master_number',
        'absolute_time',
        'option_input',
        'campaign_id',
        'campaign_name',
        'server_name',
        'call_type',
        'prompt',
        'camp_start_time',
        'camp_end_time',
        'status',
        'end_time_status',
        'delete_status',
        'call_limit'
    ];
}
