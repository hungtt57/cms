<?php

namespace App\Transformers;

use App\Models\Icheck\Product\Vendor;
use League\Fractal;

class CategoryTransformer extends Fractal\TransformerAbstract
{
    public function transform($category)
    {
        if (!is_null($category)) {
            return [
                'id' => $category->id,
                'name'  => $category->name,
            ];
        } else {
            return [];
        }
    }
}
