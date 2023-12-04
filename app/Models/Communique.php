<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Communique extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'communiques';

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];

    protected $fillabled = [
        'id',
        'title',
        'priority',
        'communique_date',
        'expiration_date',
        'location_id',
        'link',
        'description',
        'photo',
        'type',
    ];

    protected $appends = [
        "files",
        "current_date",
        "bucket_location",
    ];

    public function getFilesAttribute(){

        $files = CommuniqueFile::join('files','files.id','=','communique_files.file_id')
                ->where('communique_files.communique_id',$this->id)
                ->selectRaw('files.*')
                ->get();

        return $files;
    }

    public function getCurrentDateAttribute(){
        return Carbon::now()->format('Y-m-d');
    }

    /* public function location(){
        return $this->hasOne(Location::class,'id','locations_id');
    } */

    public function getBucketLocationAttribute(){
        $sub_seccion;

        if($this->type == "consejo"){
            $sub_seccion = 2;
        }else if($this->type == "organizacional"){
            $sub_seccion = 3;
        }else if($this->type == "institucional"){
            $sub_seccion = 4;
        }

        $locations = BucketLocation::with(['location','subgroup'])->where('origin_record_id', $this->id)
                    ->where('sub_seccion_id',$sub_seccion)
                    ->get();

        return $locations;
    }

}
