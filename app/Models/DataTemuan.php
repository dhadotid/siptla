<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DataTemuan extends Model
{
    use SoftDeletes;
    protected $table='data_temuan';
   
    function temuan()
    {
        return $this->belongsTo('App\Models\DaftarTemuan','id_lhp');
    }

    function jenistemuan()
    {
        return $this->belongsTo('App\Models\MasterTemuan','jenis_temuan_id');
    }
    
    function picunit()
    {
        return $this->belongsTo('App\Models\PICUnit','pic_temuan_id');
    }
    function levelresiko()
    {
        return $this->belongsTo('App\Models\LevelResiko','level_resiko_id');
    }
}
