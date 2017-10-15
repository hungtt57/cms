<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DistributorTitle extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'distributor_title';

    public $timestamps = false;
}
