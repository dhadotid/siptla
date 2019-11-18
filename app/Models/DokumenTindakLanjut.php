<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenTindakLanjut extends Model
{
    protected $table = 'dokumen_tindak_lanjut';

    protected $fillable = ['id_tindak_lanjut_temuan', 'nama_dokumen', 'path'];

    public function tindak_lanjut_temuan() {
        return $this->belongsTo('App\Models\TindakLanjutTemuan', 'id_tindak_lanjut_temuan');
    }
}
