<?php

namespace App\Transformers;

use App\Models\Icheck\Product\Vendor;

use League\Fractal;

class VendorTransformer extends Fractal\TransformerAbstract
{
    public function transform($vendor)
    {
        if (!is_null($vendor)) {
            return [
                'gln'  => $vendor->gln_code,
                'name'  => $vendor->name,
            ];
        } else {
            return [];
        }
    }
}
