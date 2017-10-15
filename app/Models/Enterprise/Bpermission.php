<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class Bpermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'bpermissions';
    protected $fillable = [
        'id', 'description',
    ];

    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
