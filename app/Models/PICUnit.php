<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PICUnit extends Model
{
    use SoftDeletes;
    protected $table='pic_unit';
    protected $fillable=['level_pic','bidang','fakultas','pic_1_flag','pic_2_flag','nama_pic','created_at','updated_at','deleted_at'];

    function levelpic()
    {
        return $this->belongsTo('\App\Models\LevelPIC','level_pic');
    }
    function bid()
    {
        return $this->belongsTo('\App\Models\Bidang','bidang');
    }
    
    function fak()
    {
        return $this->belongsTo('\App\Models\MasterFakultas','fakultas');
    }
}
