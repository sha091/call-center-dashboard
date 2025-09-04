<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC_Multilayer extends Model
{
    use HasFactory;
    protected $table = 'ci_multilayer';
    protected $primaryKey = 'ivr_flow_id';
    protected $fillable = [
        'cc_id',
        'layers',
        'prompt',
        'option0',
        'option1',
        'option2',
        'option3',
        'option4',
        'option5',
        'option6',
        'option7',
        'option8',
        'option9',
        'option10',
        'option11',
        'option12',
        'option13',
        'option14',
        'option15',
        'option16',
        'option17',
        'option18',
        'option19',
        'option20',
        'optiont',
        'option*',
        'option#'
    ];
}
