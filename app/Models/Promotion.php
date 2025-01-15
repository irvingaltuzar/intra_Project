<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostReactions;

class Promotion extends Model
{

    use SoftDeletes;

    protected $table = "promotions";

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    protected $appends = [
        'total_reactions'
    ];

    public function user(){
        return $this->hasOne(User::class,'full_name','user_name');
    }

    public function user_top(){
        return $this->hasOne(User::class,'full_name','user_name_top');
    }

    public function reactions(){
        return $this->hasMany(PostReactions::class,'publications_id','id')->where('publications_section_id',3);
    }

    public function getTotalReactionsAttribute(){
        $total_reactions = PostReactions::where('publications_id',$this->id)->where('publications_section_id',3)->count();
        return $total_reactions;
    }


}
