<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class JangkaWaktu extends Model
{
    use SoftDeletes;
    protected $table='jangka_waktu';
}
