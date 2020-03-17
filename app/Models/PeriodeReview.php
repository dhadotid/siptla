<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodeReview extends Model
{
    use SoftDeletes;
    protected $table='periode_review';
}
