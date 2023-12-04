<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CondolenceCollaborator extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    protected $appends = [
        "bucket_location"
    ];

    public function templanteCollaborator()
    {
        return $this->hasOne('App\Models\TemplanteCollaborator','id', 'templante_collaborator_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User','usuario', 'vw_users_usuario');
    }

    public function getBucketLocationAttribute(){

        $locations = BucketLocation::with(['location','subgroup'])->where('origin_record_id', $this->id)
                    ->where('sub_seccion_id',5)
                    ->get();

        return $locations;
    }
}
