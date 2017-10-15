<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Hook extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'hook';

    public $timestamps = false;
    protected $fillable = ['iql', 'name','type'];

    //type :
//    0 la product
//1 la category
//2 la vendor
}
