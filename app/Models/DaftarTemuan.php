<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaftarTemuan extends Model
{
    use SoftDeletes;
    protected $table='daftar_lhp';
   
    function daftar()
    {
        return $this->hasMany('App\Models\DetailTemuan','daftar_id');
    }
   
    function dpemeriksa()
    {
        return $this->belongsTo('App\Models\Pemeriksa','pemeriksa_id');
    }
    function djenisaudit()
    {
        return $this->belongsTo('App\Models\JenisAudit','jenis_audit_id');
    }
   
}
