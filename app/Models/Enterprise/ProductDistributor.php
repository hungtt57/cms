<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Product;

class ProductDistributor extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

    protected $table = 'product_distributor';

    const STATUS_DEACTIVATED = 2;
    const STATUS_ACTIVATED = 1;
    const STATUS_PENDING_ACTIVATION = 0;

    public static $statusTexts = [
        self::STATUS_ACTIVATED => 'activated',
        self::STATUS_DEACTIVATED => 'deactivated',
        self::STATUS_PENDING_ACTIVATION => 'pending activation',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['business_id', 'product_id','is_first','status','is_quota'];

    /**
     * Get all of the tags for the post.
     */
    public function business(){
        return $this->belongsTo(Business::class,'business_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function getStatusTextAttribute($value)
    {
        return static::$statusTexts[$this->attributes['status']];
    }
}
