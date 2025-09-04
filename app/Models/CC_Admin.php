<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\CC_Company_Info;

class CC_Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable ;

    protected $table = 'cc_admin';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'agent_exten',
        'full_name',
        'password',
        'email',
        'designation',
        'department',
        'status',
        'org_password',
        'cc_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(){
        return $this->belongsTo(CC_Company_Info::class, 'cc_id', 'cc_id');
    }

}
