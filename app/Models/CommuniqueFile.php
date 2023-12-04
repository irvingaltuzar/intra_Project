<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;


use Spatie\Permission\Traits\HasRoles;

class CommuniqueFile extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    use SoftDeletes;

    protected $table = 'communique_files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'file_id',
        'communique_id',
    ];

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s',
        'updated_at' => 'datetime:Y-m-d h:i:s',
        'deleted_at' => 'datetime:Y-m-d h:i:s'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

}
