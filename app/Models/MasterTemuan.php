<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterTemuan extends Model
{
    use SoftDeletes;
    protected $table='master_temuan';
    protected $fillable=[
        'code','temuan','desc','flag','created_at','updated_at','deleted_at'
    ];
}
