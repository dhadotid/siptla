<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DataRekomendasi extends Model
{
    use SoftDeletes;
    protected $table='data_rekomendasi';

    function dtemuan()
    {
        return $this->belongsTo('App\Models\DataTemuan','id_temuan');
    }
    function jenistemuan()
    {
        return $this->belongsTo('App\Models\MasterTemuan','jenis_temuan');
    }

    function picunit1()
    {
        return $this->belongsTo('App\Models\PICUnit','pic_1_temuan_id');
    }

    function picunit2()
    {
        return $this->belongsTo('App\Models\PICUnit','pic_2_temuan_id');
    }
    function jangkawaktu()
    {
        return $this->belongsTo('App\Models\JangkaWaktu','jangka_waktu_id');
    }
    function statusrekomendasi()
    {
        return $this->belongsTo('App\Models\StatusRekomendasi','status_rekomendasi_id');
    }
    function drekanan()
    {
        return $this->belongsTo('App\Models\DaftarRekanan','rekanan');
    }
}
