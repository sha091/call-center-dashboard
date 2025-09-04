<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Client_List extends Model
{
    use HasFactory;
    protected $table = 'cc_client_list';
    protected $fillable = [
        'city',
        'client_name',
        'poc_name',
        'contact',
        'email',
        'cc_id'
    ];
}
