<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Login_Activity extends Model
{
    use HasFactory;
    protected $table = 'cc_login_activity';
    protected $fillable = [
        'staff_id',
        'login_datetime',
        'logout_datetime',
        'status',
        'update_datetime',
        'cc_id'
    ];
}
