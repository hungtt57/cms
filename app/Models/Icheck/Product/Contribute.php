<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Country;
use App\Models\Icheck\Product\Product;
class Contribute extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'contribute';

    protected $fillable = [
        'gtin_code', 'product_name', 'price',
        'attachments', 'company', 'address', 'phone',
        'note','seller_phone','categories','properties'
    ];


    const STATUS_DISAPPROVED = 2;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING_APPROVAL = 0;
    const STATUS_IN_PROGRESS = 3;

    public static $statusTexts = [
        self::STATUS_DISAPPROVED => 'disapproved',
        self::STATUS_APPROVED => 'approved',
        self::STATUS_PENDING_APPROVAL => 'pending approval',
        self::STATUS_IN_PROGRESS => 'in progress',
    ];
    const CREATED_AT = 'createdAt';
    public $timestamps = false;

    public function getIsApprovedAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_APPROVED;
    }
    public function getIsDisapprovedAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_DISAPPROVED;
    }
    public function getIsPendingActivationAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_PENDING_APPROVAL;
    }
    public function product(){
        return $this->belongsTo(Product::class,'gtin_code','gtin_code');
    }
    public function categories(){
       return $this->product->categories();
    }
    public function attributes()
    {
        return $this->product->attributes();
    }
    public function getAttr(){
        $content = null;
        foreach ($this->product->attributes()->get() as $attr){
            if($attr->id == 1){
                $content = $attr->pivot->content;
                return $content;
            }

        }
        return $content;

    }
    public function vendor(){
        return  $this->product->vendor;
    }
    public function getImageExist(){
        $product = $this->product;
        $images = array();
        if ($product->image_default) {
            $images[] =get_image_url($product->image_default);
        }

        if($product->pproduct && isset($product->pproduct->attachments)){

            foreach ($product->pproduct->attachments as $value){

                if(isset($value['type'])) {
                    if ($value['type'] == 'image') {
                        $images[] = get_image_url($value['link']);

                    }
                }
            }

        }

        return $images;
    }

}
