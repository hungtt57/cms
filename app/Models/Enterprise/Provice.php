<?php

namespace App\Models\Enterprise;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Provice extends Authenticatable
{
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id', 'level','type'
    ];
}
