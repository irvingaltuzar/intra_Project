<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolesItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "roles_items";

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    public function sub_seccion(){
        return $this->hasOne(SubSeccion::class,'id','sub_seccion_id')->with('seccion');
    }

}
