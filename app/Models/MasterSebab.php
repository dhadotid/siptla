<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSebab extends Model
{
    use SoftDeletes;
    protected $table='master_sebab';
    protected $fillable=[
        'code','sebab','desc','flag','created_at','updated_at','deleted_at'
    ];
}
