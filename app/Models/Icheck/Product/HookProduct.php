<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HookProduct extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'hook_product';

    public $timestamps = false;
    protected $fillable = ['hook_id', 'product_id','start_date','end_date'];

        public function product(){
            return $this->belongsTo(Product::class,'product_id','gtin_code');
        }

}
