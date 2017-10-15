<?php

namespace App\Models\Enterprise\ProductReview;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'product_review_groups';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'categories' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['icheck_id', 'name', 'categories', 'review_count', 'max_review'];

    /**
     * Get all of the tags for the post.
     */
    public function members()
    {
        return $this->belongsToMany(FacebookId::class, 'product_review_group_facebook_id', 'group_id', 'facebook_id');
    }
}
