<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\AttrDynamic;

class Category extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'category';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    public $timestamps = false;


    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
    public function attributes(){
        $attributes =  $this->attributes['attributes'];
        $attr = null;
        if($attributes){
            if($this->attributes['id']){
                $attributes = explode(',',$attributes);
                $attr = AttrDynamic::whereIn('id',$attributes)->get();
            }
        }
        return $attr;
    }
    public function child(){
        return $this->hasOne(Category::class,'parent_id','id');
    }
}
