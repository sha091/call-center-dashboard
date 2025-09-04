<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Bulk_Obd extends Model
{
    use HasFactory;
    protected $table = 'cc_bulk_obd';
    protected $primaryKey = 's_no';
    protected $fillable = [
        's_no',
        'campaign_id',
        'tracking_id',
        'vpbx_id',
        'caller_id',
        'telco',
        'unique_id',
        'order_no',
        'amount_no',
        'response',
        'input',
        'call_start',
        'call_end',
        'call_duration',
        'call_duration_complete',
        'retry_times',
        'status',
        'msg_status',
        'delete_status',
        'api_request',
        'api_res',
        'misscall_datetime',
        'outbound_call_time',
        'outbound_retry_time',
        'called_datetime'
    ];
}
