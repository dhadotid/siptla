<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class JenisAudit extends Model
{
    use SoftDeletes;
    protected $table='jenis_audit';
}
