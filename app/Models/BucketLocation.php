<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BucketLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "bucket_locations";

    public $timestamps = true;

    public function location(){
        return $this->hasOne(Location::class,'id','locations_id');
    }

    public function subgroup(){
        return $this->hasOne(Subgroup::class,'id','subgroups_id');
    }
}
