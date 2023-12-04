<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalPosting extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = "internal_postings";

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    protected $appends = [
        "bucket_location",
        "files"
    ];

    public function getFilesAttribute(){

        $files = BucketFile::join('files','files.id','=','bucket_files.file_id')
                ->where('bucket_files.sub_seccion_id',9)
                ->where('bucket_files.origin_record_id',$this->id)
                ->selectRaw('files.*')
                ->get();

        return $files;
    }

    public function getBucketLocationAttribute(){

        $locations = BucketLocation::with(['location','subgroup'])->where('origin_record_id', $this->id)
                    ->where('sub_seccion_id',9)
                    ->get();

        return $locations;
    }

}
