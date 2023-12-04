<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Seccion;
use App\Models\ProjectBoardCategory;

class ProjectBoard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "project_board";

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    protected $appends = [
        "owner",
        "leader",
    ];

    public function category(){
        return $this->hasOne(ProjectBoardCategory::class,'id','project_board_categories_id');
    }
    public function getOwnerAttribute(){
        
        $user = User::where('usuario',$this->owner_usuario)->first();
        return $user;
    }
    public function getLeaderAttribute(){
        
        $user = User::where('usuario',$this->leader_usuario)->first();
        return $user;
    }


}
