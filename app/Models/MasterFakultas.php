<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterFakultas extends Model
{
    use SoftDeletes;
    protected $table='master_fakultas';
    protected $fillable = ['nama_dinas','singkatan','alamat','flag','created_at','updated_at','deleted_at'];

    function dinas()
    {
        return $this->hasMany('App\Models\PivotUserDinas','dinas_id');
    }
}
