<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Seccion;

class SubSeccion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "sub_seccion";

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    public function seccion(){
        return $this->hasOne(Seccion::class,'id','seccion_id');
    }
}
