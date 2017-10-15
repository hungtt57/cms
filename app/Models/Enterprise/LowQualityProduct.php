<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class LowQualityProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'low_quality_product';
    protected $fillable = [
        'gtin_code','found','scan'
    ];
    public $timestamps = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
