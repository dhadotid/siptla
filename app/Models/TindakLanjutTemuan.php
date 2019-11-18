<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TindakLanjutTemuan extends Model
{
    use SoftDeletes;
    protected $table = 'tindak_lanjut_temuan';
    protected $fillable = ['detail_id','tindak_lanjut','keterangan','created_at','updated_at','deleted_at'];

    function detail()
    {
        return $this->belongsTo('App\Models\DetailTemuan','detail_id');
    }

    public function dokumen_tindak_lanjut() {
        return $this->hasMany('App\Models\DokumenTindakLanjut', 'id_tindak_lanjut_temuan');
    }
}
