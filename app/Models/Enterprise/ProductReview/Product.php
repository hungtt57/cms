<?php

namespace App\Models\Enterprise\ProductReview;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enterprise\Collaborator;

class Product extends Model
{
    protected $table = 'product_review_products';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cached_info' => 'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['gtin', 'review_count', 'max_review', 'cached_info'];

    public function reviewingBy()
    {
        return $this->belongsTo(Collaborator::class, 'reviewing_by');
    }
}
