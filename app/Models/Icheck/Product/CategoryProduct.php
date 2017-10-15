<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Country;


class CategoryProduct extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'category_product';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
