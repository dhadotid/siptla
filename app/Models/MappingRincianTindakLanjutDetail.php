<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MappingRincianTindakLanjutDetail extends Model
{
    use SoftDeletes;
    protected $table = 'mapping_rincian_tindak_lanjut_detail';

    function dtemuan()
    {
        return $this->belongsTo('App\Models\DataTemuan','id_temuan');
    }

    function drekom()
    {
        return $this->belongsTo('App\Models\DataRekomendasi','id_rekomendasi');
    }
}
