<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'product_info';


    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
}



?>