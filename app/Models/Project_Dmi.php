<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project_Dmi extends Model
{
    use SoftDeletes;
    
    protected $table = 'project_dmi';
    
    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    public function items(){
        return $this->hasMany(Project_Dmi_Item::class,'project_dmi_id');
    }
}
