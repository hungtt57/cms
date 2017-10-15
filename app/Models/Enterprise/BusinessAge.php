<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class BusinessAge extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    protected $table = 'business_age';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['business_id', 'date','total','age','18-24','25-34','35-44','45-54','55-64','65+'];


    /**
     * Get all of the tags for the post.
     */

}
