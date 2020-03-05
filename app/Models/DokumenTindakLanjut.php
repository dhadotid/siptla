<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DokumenTindakLanjut extends Model
{
    use SoftDeletes;
    protected $table = 'dokumen_tindak_lanjut';

    protected $fillable = ['id_tindak_lanjut_temuan', 'nama_dokumen', 'path'];

    public function dokumen_tindak_lanjut() {
        return $this->belongsTo('App\Models\TindakLanjutTemuan', 'id_tindak_lanjut_temuan');
    }
}
