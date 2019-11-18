<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterRekomendasi extends Model
{
    use SoftDeletes;
    protected $table='master_rekomendasi';
    protected $fillable=[
        'code','rekomendasi','desc','flag','created_at','updated_at','deleted_at'
    ];
}
