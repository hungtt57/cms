<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;

use App\Models\Icheck\Product\Category;
class Product extends Model
{
    const STATUS_DISAPPROVED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING_APPROVAL = 2;
    const STATUS_PENDING_DELETE = 3;

    public static $statusTexts = [
        self::STATUS_DISAPPROVED => 'disapproved',
        self::STATUS_APPROVED => 'approved',
        self::STATUS_PENDING_APPROVAL => 'pending approval',
        self::STATUS_PENDING_DELETE => 'pending delete',
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
    protected $fillable = ['barcode', 'name', 'image', 'price', 'status',
        'attrs', 'warning','reason','is_exist','is_quota','properties','gln_id'];

    public function GLN()
    {
        return $this->belongsTo(GLN::class, 'gln_id');
    }

    /**
     * Get all of the tags for the post.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

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

        return 'http://ucontent.icheck.vn/' . $this->getAttribute('image') . '_' . $size . '.jpg';
    }


}
