<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class LogSearchVendor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'log_search_vendor';
    protected $fillable = [
        'email','key'
    ];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
