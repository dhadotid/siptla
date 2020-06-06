<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MappingRekomendasiNotifikasi extends Model
{
    use SoftDeletes;
    protected $table = 'rekomendasi_mapping_notification';

    function dtemuan()
    {
        return $this->belongsTo('App\Models\DataTemuan','id_temuan');
    }

    function drekom()
    {
        return $this->belongsTo('App\Models\DataRekomendasi','id_rekomendasi');
    }

    function dlhp()
    {
        return $this->belongsTo('App\Models\DaftarTemuan','id_lhp');
    }
}
