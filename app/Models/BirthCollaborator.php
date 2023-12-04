<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BirthCollaborator extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    public function templanteCollaborator()
    {
        return $this->hasOne('App\Models\TemplanteCollaborator','id', 'templante_collaborator_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User','usuario', 'vw_users_usuario');
    }
}
