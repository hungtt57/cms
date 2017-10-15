<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class BusinessLocation extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    protected $table = 'business_location';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['business_id', 'date','scan','location','show','like','comment'];


    /**
     * Get all of the tags for the post.
     */

}
