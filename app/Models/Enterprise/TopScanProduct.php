<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

class TopScanProduct extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'top_scan_products';
    protected $fillable = [
        'gtin','order',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
