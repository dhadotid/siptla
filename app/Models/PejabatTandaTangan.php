<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PejabatTandaTangan extends Model
{
    use SoftDeletes;
    protected $table='pejabat_tandatangan';
}
