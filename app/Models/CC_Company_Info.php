<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CC_Admin;

class CC_Company_Info extends Model
{
    use HasFactory;
    protected $table = 'cc_company_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'company_name',
        'master_number',
        'cc_id',
        'address',
        'poc_name',
        'status',
        'auto_detection'
    ];

    // Optional: One company has many admins
    public function admins()
    {
        return $this->hasMany(CC_Admin::class, 'cc_id', 'cc_id');
    }
}
