<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaftarRekanan extends Model
{
    use SoftDeletes;
    protected $table='daftar_rekanan';
}
