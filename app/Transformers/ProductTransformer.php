<?php

namespace App\Transformers;

use App\Models\Enterprise\Product;
use League\Fractal;

class ProductTransformer extends Fractal\TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id'    => $product->id,
            'name'  => $product->name,
            'barcode'  => $product->barcode,
            'status'    => Product::$statusTexts[$product->status],
            'created_at'    => $product->created_at->toIso8601String(),
            'links'    => [
                'edit' => route('Staff::Management::product@edit', [$product->id]),
                'approve' => route('Staff::Management::product@approve', [$product->id]),
                'delete' => route('Staff::Management::product@delete', [$product->id]),
            ],
        ];
    }
}
