<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\Passport;

use Spatie\Permission\Traits\HasRoles;

class File extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    use SoftDeletes;
    
    protected $table = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'file',
        'extension',
        'type_file',
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
