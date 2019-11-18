<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterBidangPengawasan extends Model
{
    use SoftDeletes;
    protected $table='master_bidang_pengawasan';
    protected $fillable = [
        'code','bidang','flag','created_at','updated_at','deleted_at'
    ];
}
