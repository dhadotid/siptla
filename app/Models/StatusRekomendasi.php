<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class StatusRekomendasi extends Model
{
    use SoftDeletes;
    protected $table='status_rekomendasi';
}
