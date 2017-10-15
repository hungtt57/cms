<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Category;

class ProductCategory extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    public $timestamps = false;

    protected $table = 'product_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'product_id'];

    /**
     * Get all of the tags for the post.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
