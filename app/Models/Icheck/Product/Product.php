<?php

namespace App\Models\Icheck\Product;


use Illuminate\Database\Eloquent\Model;


use Jenssegers\Mongodb\Eloquent\HybridRelations;
use App\Models\Enterprise\MICheckReport;

use App\Models\Mongo\Product\PProduct;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\ProductDistributor;
use Illuminate\Support\Facades\Log;
use App\Models\BarcodeViet\MSMVGTIN;
use App\Models\Icheck\Product\ProductInfo;
use Event;
use App\Models\Enterprise\ProductDistributorTemp;
use App\Models\Icheck\Product\SearchNotFound;
class Product extends Model
{
    use HybridRelations;
    protected $connection = 'icheck_product';

    protected $table = 'product';

    const STATUS_DISAPPROVED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING_APPROVAL = 2;
    const STATUS_PENDING_DELETE = 3;


    const BUSINESS_VERIFY_OWNER = 1;
    public static $statusTexts = [
        self::STATUS_DISAPPROVED => 'disapproved',
        self::STATUS_APPROVED => 'approved',
        self::STATUS_PENDING_APPROVAL => 'pending approval',
        self::STATUS_PENDING_DELETE => 'pending delete',
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
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
    protected $fillable = ['gtin_code', 'internal_code', 'vendor_id', 'product_name',
        'image_default', 'price_default','status','keywords','score','searched','features',
        'in_categories','currency_default','mapped',
        'date_validity','expiration_date'
    ];


    /**
     * Get all of the tags for the post.
     */
//

    public function getStatusTextAttribute($value)
    {
        return static::$statusTexts[$this->attributes['status']];
    }

    public function getIsApprovedAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_ACTIVATED;
    }

    public function getIsPendingActivationAttribute($value)
    {
        return $this->attributes['status'] === static::STATUS_PENDING_ACTIVATION;
    }

    public function image($size = 'original')
    {
        $sizes = ["original", "thumb_small", "thumb_medium", "thumb_large", "small", "medium", "large"];

        if (!in_array($size, $sizes)) {
            $size = 'original';
        }

        return 'http://ucontent.icheck.vn/' . $this->getAttribute('image_default') . '_' . $size . '.jpg';
    }


    public function mdata()
    {
        return $this->hasOne(MProduct::class, 'gtin_code', 'gtin_code');
    }

    public function vendor2()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function reports()
    {
        return $this->hasMany(MICheckReport::class, 'target', 'gtin_code');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function attributes()
    {
        return $this->belongsToMany(ProductAttr::class, 'product_info', 'product_id', 'attribute_id')->withPivot(['content','content_text','short_content']);
    }
    public function pproduct(){
        return $this->hasOne(PProduct::class, 'gtin_code', 'gtin_code');
    }

    public  function message(){
        return $this->hasOne(ProductMessage::class,'gtin_code','gtin_code');
    }

    public function productsDistributor()
    {
        return $this->belongsToMany(Business::class, env('DB_DATABASE').'.product_distributor', 'product_id','business_id')->withPivot(['is_first','status','is_quota']);
    }

    public function productsDistributorTemp(){
        return $this->hasOne(ProductDistributorTemp::class,'gtin_code','gtin_code');
    }
//    public static function boot()
//    {
//        parent::boot();
//
//        static::saved(function($product){
//            Event::fire('product.saved', $product);
//        });
//        static::updated(function($product){
//            Event::fire('product.saved', $product);
//        });
//        static::created(function($product){
//            Event::fire('product.saved', $product);
//        });
//    }

    public function searchNotFound(){
        return $this->belongsTo(SearchNotFound::class , 'gtin_code', 'gtin_code');
    }
    public function properties(){
        return $this->belongsTo(AttrValue::class, 'id','product_id');
    }
    public function currency(){
        return $this->hasOne(Currency::class,'id','currency_default');
    }
    public function MSMVGTIN(){
        return $this->hasOne(MSMVGTIN::class,'gtin_code','gtin_code');
    }
}
