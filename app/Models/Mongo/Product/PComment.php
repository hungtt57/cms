<?php

namespace App\Models\Mongo\Product;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Icheck\User\Account;
use App\Models\Icheck\Product\Product;
class PComment extends Model
{
    protected $connection = 'icheck_product_mongo';
    protected $collection = 'p_comment';
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    // atribute : addPoint
    const ADDED_POINT = 1;
    const NOT_ADDED_POINT = 0;
    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';

    public function childs()
    {

        $comment = PComment::where('parent',$this->attributes['_id'])->where('deleted_at',null)->orderBy('createdAt','desc')->get();
        return $comment;

    }
    public function product(){
        return $this->hasOne(Product::class,'gtin_code','object_id');
    }

    public function getId2Attribute($value)
    {
        return $this->attributes['_id'];
    }
    public function account(){


            $icheck_id = $this->attributes['owner']['icheck_id'];
            $account = Account::where('icheck_id',$icheck_id)->first();
            return $account;



    }
}
