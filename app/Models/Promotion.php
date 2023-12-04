<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];


    public function user(){
        return $this->hasOne(User::class,'full_name','user_name');
    }

    public function user_top(){
        return $this->hasOne(User::class,'full_name','user_name_top');
    }
}
