<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaftarTemuan extends Model
{
    use SoftDeletes;
    protected $table='daftar_temuan';
    protected $fillable = [
        'aparat_id','dinas_id','tahun','flag','pengawasan_id','no_pengawasan','tgl_pengawasan','created_at','updated_at','deleted_at'
    ];

    function aparat()
    {
        return $this->belongsTo('App\Models\MasterDinas','aparat_id');
    }
    function dinas()
    {
        return $this->belongsTo('App\Models\MasterDinas','dinas_id');
    }
    function daftar()
    {
        return $this->hasMany('App\Models\DetailTemuan','daftar_id');
    }
    function pengawasan()
    {
        return $this->belongsTo('App\Models\MasterBidangPengawasan','pengawasan_id');
    }
}
