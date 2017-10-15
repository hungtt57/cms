<?php

namespace App\Models\Enterprise;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Enterprise\Role;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password','manager_id','quota_search'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class,'user_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'user_permission')->withPivot('value');
    }

//    public function sales(){
//        return $this->hasMany(User::class, 'manager_id', 'id');
//    }
}
