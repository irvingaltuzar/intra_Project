<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConmmemorativeDate extends Model
{
    use SoftDeletes;

    protected $table = 'conmmemorative_date';


    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s',
        'publication_date' => 'datetime:Y-m-d'
    ];

    protected $appends = [
        "bucket_location",
    ];

    public function getBucketLocationAttribute(){

        $locations = BucketLocation::with(['location','subgroup'])->where('origin_record_id', $this->id)
                    ->where('sub_seccion_id',8)
                    ->get();

        return $locations;
    }

}
