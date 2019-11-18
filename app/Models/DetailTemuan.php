<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTemuan extends Model
{
    use SoftDeletes;
    protected $table = 'detail_temuan';
    protected $fillable = [
        'daftar_id','pengawasan_id','temuan_id','sebab_id','rekomendasi_id','no_pengawasan','tgl_pengawasan','uraian_temuan','uraian_rekomendasi','flag','penyebab','created_at','updated_at','deleted_at'
    
    ];

    public function tindak_lanjut_temuan() 
    {
        return $this->hasOne('App\Models\TindakLanjutTemuan', 'detail_id');
    }

    function daftar()
    {
        return $this->belongsTo('App\Models\DaftarTemuan','daftar_id');
    }
    function pengawasan()
    {
        return $this->belongsTo('App\Models\MasterBidangPengawasan','pengawasan_id');
    }
    function temuan()
    {
        return $this->belongsTo('App\Models\MasterTemuan','temuan_id');
    }
    function sebab()
    {
        return $this->belongsTo('App\Models\MasterSebab','sebab_id');
    }
    function rekomendasi()
    {
        return $this->belongsTo('App\Models\MasterRekomendasi','rekomendasi_id');
    }
}
