<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;


class AttrValue extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'attr_value';

    protected $fillable = [
        'product_id', 'attribute_id', 'content'

    ];
   public $timestamps = false;
}
