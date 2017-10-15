<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DistributorProduct extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'distributor_product';

    protected $fillable = [
        'product_id', 'distributor_id', 'is_monopoly'

    ];

    public $timestamps = false;
}
