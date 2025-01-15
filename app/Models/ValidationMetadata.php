<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValidationMetadata extends Model
{
    use SoftDeletes;

	protected $table = 'validation_metadata';

	public $timestamps = true;

	protected $casts = [
		'created_at' => "datetime:Y-m-d h:i:s",
		'updated_at' => 'datetime:Y-m-d h:i:s',
		'deleted_at' => 'datetime:Y-m-d h:i:s',
	];
}
