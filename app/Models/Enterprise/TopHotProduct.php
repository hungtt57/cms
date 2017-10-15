<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class TopHotProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'top_hot_products';
    protected $fillable = [
        'gtin','order',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
