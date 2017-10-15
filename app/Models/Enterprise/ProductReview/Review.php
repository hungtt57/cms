<?php

namespace App\Models\Enterprise\ProductReview;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enterprise\Collaborator;
use App\Models\Enterprise\User;

class Review extends Model
{
    const STATUS_DISAPPROVED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING_APPROVAL = 2;
    const STATUS_IN_PROGRESS = 3;
    const STATUS_ERROR = 4;

    public static $statusTexts = [
        self::STATUS_DISAPPROVED => 'disapproved',
        self::STATUS_APPROVED => 'approved',
        self::STATUS_PENDING_APPROVAL => 'pending approval',
        self::STATUS_IN_PROGRESS => 'in progress',
        self::STATUS_ERROR => 'error',
    ];

    protected $table = 'product_review_reviews';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'categories' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['approved_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'status', 'note', 'error_message', 'approved_at', 'price'];

    public function reviewer()
    {
        return $this->belongsTo(Collaborator::class, 'review_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'gtin', 'gtin');
    }

    public function getStatusTextAttribute($value)
    {
        return static::$statusTexts[$this->attributes['status']];
    }
}
