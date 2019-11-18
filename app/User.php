<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','nip','pangkat','golongan','jabatan','level','flag'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    function user()
    {
        return $this->hasOne('App\Models\PivotUserDinas','user_id');
    }
    
    function userkepala(){
		// return $this->hasMany('App\Models\MasterKepalaDinas','user_id');
		return $this->hasOne('App\Models\MasterKepalaDinas','user_id');
	}
}
