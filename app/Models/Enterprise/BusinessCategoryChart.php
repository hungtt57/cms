<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class BusinessCategoryChart extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    protected $table = 'business_category_chart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id','business_id', 'date','total','show','scan','like','comment'];


    /**
     * Get all of the tags for the post.
     */

}
