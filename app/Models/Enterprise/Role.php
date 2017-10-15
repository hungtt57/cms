<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'roles';
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'role_permission')->withPivot('value');
    }
    public function users(){
        return $this->belongsToMany(User::class,'user_role');
    }
}
