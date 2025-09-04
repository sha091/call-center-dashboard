<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Sounds extends Model
{
    use HasFactory;
    protected $table = 'cc_sounds';
    protected $primaryKey = 'sound_id';
    protected $fillable = [
        'sound_id',        
        'cc_id',
        'file_name',
        'path',
    ];
}
