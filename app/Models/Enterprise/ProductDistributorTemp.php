<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

use App\Models\Icheck\Product\Category;
class ProductDistributorTemp extends Model
{

    const STATUS_DISAPPROVED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING_APPROVAL = 2;


    public static $statusTexts = [
        self::STATUS_DISAPPROVED => 'disapproved',
        self::STATUS_APPROVED => 'approved',
        self::STATUS_PENDING_APPROVAL => 'pending approval',

    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'attrs' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $fillable = ['gtin_code', 'product_name', 'image','attrs',
        'price', 'status','categories','business_id','properties','reason'];

//    public function GLN()
//    {
//        return $this->belongsTo(GLN::class, 'gln_id');
//    }

    /**
     * Get all of the tags for the post.
     */
//    public function categories()
//    {
//        return $this->belongsToMany(Category::class, 'product_category');
//    }
//
    public function getStatusTextAttribute()
    {
        return static::$statusTexts[$this->attributes['status']];
    }
    public function getAttr($value){

            $attrs = json_decode(json_decode($this->attributes['attrs']));

        if($attrs){
            foreach ($attrs as $key => $content){
                if($value == $key){
                    return $content;
                }
            }

        }



    }
//    public function getIsApprovedAttribute($value)
//    {
//        return $this->attributes['status'] === static::STATUS_ACTIVATED;
//    }
//
//    public function getIsPendingActivationAttribute($value)
//    {
//        return $this->attributes['status'] === static::STATUS_PENDING_ACTIVATION;
//    }
//
    public function image($size = 'original')
    {
        $sizes = ["original", "thumb_small", "thumb_medium", "thumb_large", "small", "medium", "large"];

        if (!in_array($size, $sizes)) {
            $size = 'original';
        }

        return 'http://ucontent.icheck.vn/' . $this->getAttribute('image') . '_' . $size . '.jpg';
    }
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }

}
