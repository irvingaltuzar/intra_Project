<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Carbon\Carbon;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "comments";

    public $timestamps = true;

    protected $appends = [
        'user',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];



    public function user(){
        return $this->hasOne(User::class,'usuario','vw_users_usuario');
    }

    // public function getUserAttribute()
    // {
    //     return Carbon::format()
    // }

    public function getUserAttribute(){
        $user = User::where('usuario',$this->vw_users_usuario)->first();
        /* $photo = asset('image/icons/user.svg');
        if($user != null){
            if($user->photo == ""){
                if(strtoupper($user->sex) == 'MASCULINO'){
                    $photo = asset('image/icons/masculino.svg');
                }else{
                    $photo = asset('image/icons/femenino.svg');
                }
             }else{
                 $photo = $user->photo;
             }
        }

        $full_name = "$user->name $user->last_name";

        return ['full_name'=>$full_name,'photo'=>$photo]; */
        return $user;
    }

    public function childrenComments(){
        return $this->hasMany(Comment::class,'parent_comments_id','id')->with('childrenComments');
    }



}
