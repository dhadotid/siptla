<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LevelResiko extends Model
{
    use SoftDeletes;
    protected $table='level_resiko';
}
