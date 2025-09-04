<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Working_Hours extends Model
{
    use HasFactory;
    protected $table = 'cc_working_hours';
    protected $primaryKey = 'working_id';

    protected $fillable = [
        'cc_id',
        'today',
        'start_time',
        'end_time',
        'option_off_time',
        'extensions_number',
    ];

}
