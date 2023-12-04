<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "events";

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    protected $appends = [
        "bucket_location",
        "files",
    ];

    public $timestamps = true;

    public function type_event(){
        return $this->hasOne(TypeEvent::class,'id','type_event_id');
    }

    public function getBucketLocationAttribute(){

        $locations = BucketLocation::with(['location','subgroup'])->where('origin_record_id', $this->id)
                    ->where('sub_seccion_id',15)
                    ->get();

        return $locations;
    }

    public function getFilesAttribute(){

        $files = BucketFile::join('files','files.id','=','bucket_files.file_id')
                ->where('bucket_files.sub_seccion_id',15)
                ->where('bucket_files.origin_record_id',$this->id)
                ->selectRaw('files.*')
                ->get();

        return $files;
    }



}
