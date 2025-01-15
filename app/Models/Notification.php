<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "notifications";

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    protected $appends = [
        'usuario_notifying_photo',
    ];

    public function getUsuarioNotifyingPhotoAttribute(){
        $user = User::where('usuario',$this->usuario_notifying)->first();
        
        return $user != null ? $user->photo_src : null;


    }

}
