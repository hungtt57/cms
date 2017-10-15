<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Country;
use App\Models\Icheck\Product\Product;

class Vendor extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'vendor';

    protected $fillable = [
        'gln_code', 'internal_code', 'name',
        'address', 'phone', 'email', 'website',
        'country_id','other','verify_by','prefix'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function getNameAttribute(){

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->post('localhost:1337/decrypt', [
                'form_params' => [
                    'content' => $this->attributes['name']
                ]
            ]);

            $res = (string) $res->getBody();
        }catch (\Exception $e) {
            return '';
            return $e->getResponse()->getBody();
        }
        return $res;

    }
//
    public function getAddressAttribute(){

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->post('localhost:1337/decrypt', [
                'form_params' => [
                    'content' => $this->attributes['address']
                ]
            ]);

            $res = (string) $res->getBody();
        }catch (\Exception $e) {
            return '';
        }
        return $res;
    }
    public static function encrypt($value){
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('POST','localhost:1337/encrypt', [
                'form_params' => [
                    'content' => $value,
                ]
            ]);

            $res = (string) $res->getBody();
        }catch (\Exception $e) {
            throw $e;
        }
        return $res;
    }
}
