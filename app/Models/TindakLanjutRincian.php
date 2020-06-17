<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakLanjutRincian extends Model
{
    use SoftDeletes;
    protected $table = 'tindak_lanjut_rincian';

    function rekomendasi()
    {
        return $this->belongsTo('App\Models\DataRekomendasi','rekomendasi_id');
    }
    function temuan()
    {
        return $this->belongsTo('App\Models\DataTemuan','temuan_id');
    }
    function lhp()
    {
        return $this->belongsTo('App\Models\DaftarTemuan','lhp_id');
    }
    function pic1()
    {
        return $this->belongsTo('App\Models\PICUnit','pic_1_id');
    }
    function unit_kerja()
    {
        return $this->belongsTo('App\Models\PICUnit','unit_kerja_id');
    }
    function pic2()
    {
        return $this->belongsTo('App\Models\PICUnit','pic_2_id');
    }

    public function dokumen_tindak_lanjut() {
        return $this->hasMany('App\Models\DokumenTindakLanjut', 'id_tindak_lanjut_temuan');
    }

    function bankTujuan(){
        return $this->belongsTo('App\Models\BankList','bank_tujuan');
    }
}
