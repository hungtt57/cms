<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class CategoryData extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    protected $table = 'category_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'date','scan','like','comment'];
    protected  $timestamp = NULL;

    /**
     * Get all of the tags for the post.
     */

}
