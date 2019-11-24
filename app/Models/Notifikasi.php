<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table='notifikasi';

    function dari()
    {
        return $this->belongsTo('App\User','dari');
    }
    function kepada()
    {
        return $this->belongsTo('App\User','kepada');
    }
}
