<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    protected $table = 'vw_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuarioId',
        'usuario',
        'personal_id',
        'name',
        'last_name',
        'birth',
        'sex',
        'email',
        'extension',
        'photo',
        'position_company',
        'deparment',
        'date_admission',
        'antiquity_date',
        'location',
        'company_name',
        'company_code',
        'branch_code',
        'plaza_id',
        'top_plaza_id',
        'status'
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
        /* 'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s' */
    ];

    /* public $timestamps = true; */

    protected $appends = [
        //'full_name',
        'photo_src'
    ];

    public function locations()
    {
        return $this->hasOne('App\Models\Location','name', 'location');
    }

    public function subgroups()
    {
        return $this->hasOne('App\Models\Subgroup','name', 'group');
    }

    public function publications()
    {
        return $this->hasMany(Publication::class, 'vw_users_usuario', 'usuario');
    }

    public function horary()
    {
        return $this->hasMany('App\Models\Horary','id_empleado', 'usuario')->where('estatus', 'Aprobado');
    }

    public function organigrama(){
        return $this->hasMany(User::class,'top_plaza_id','plaza_id')->with('organigrama');
    }

    public function commanding_staff(){
		return $this->hasOne(User::class,'plaza_id','top_plaza_id');
	}

    public function publication_birthday(){
        $year = Carbon::now()->format('Y');
		return $this->publications()->where('aux_key_publication', 'like', "%_${year}-%")
                                    ->where('publications_section_id',1);
    }

    /* public function getFullNameAttribute(){
        return $this->name.' '.$this->last_name;
    } */

    public function getPhotoSrcAttribute()
    {
        $file_path = $this->photo;

        $pattern = '/\/i';

        $short_path = preg_replace("#([\\\\/]+)#", '/', substr($file_path, 29));

        return env('APP_FILES_URL') . $short_path;
    }
}
