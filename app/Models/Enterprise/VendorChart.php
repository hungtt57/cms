<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class VendorChart extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    protected $table = 'vendor_chart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vendor_id', 'date','total'];
    protected  $timestamp = NULL;

    /**
     * Get all of the tags for the post.
     */

}
