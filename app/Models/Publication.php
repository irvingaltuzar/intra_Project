<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;

class Publication extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "publications";

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    public function comments(){
        return $this->hasMany(Comment::class,'publications_id');
    }

    public function comments_complete(){
        return $this->comments();
    }

    public function user(){
        return $this->hasOne(User::class,'usuario','vw_users_usuario');
    }

}
