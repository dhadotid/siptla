<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RincianUmum extends Model
{
    use SoftDeletes;

    protected $table='rincian_umum';

    function dtemuan()
    {
        return $this->belongsTo('App\Models\DataTemuan','id_temuan');
    }

    function drekomendasi()
    {
        return $this->belongsTo('App\Models\DataRekomendasi','id_rekomendasi');
    }
    
    function dunitkerja()
    {
        return $this->belongsTo('App\Models\PICUnit','unit_kerja_id');
    }
}