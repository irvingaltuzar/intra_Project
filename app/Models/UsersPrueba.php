<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class UsersPrueba extends Authenticatable
{
    protected $table = 'user_prueba';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vw_users_usuario',
        'usuario',
        'personal_id',
        'name',
        'last_name',
        'position_company',
        'deparment',
        'plaza_id',
        'top_plaza_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */


    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];


    public function organigrama(){
        return $this->hasMany(UsersPrueba::class,'top_plaza_id','plaza_id')->with('organigrama');
    }
}
