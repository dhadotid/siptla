<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    protected $table='review';

    function lhp()
    {
        return $this->belongsTo('App\Models\DaftarTemuan','id_lhp');
    }

    function reviewer()
    {
        return $this->belongsTo('App\User','reviewer_id');
    }

    function tanggapan()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
