<?php

namespace App\Models\Enterprise\ProductReview;

use Illuminate\Database\Eloquent\Model;

class FacebookId extends Model
{
    public $incrementing = false;

    protected $table = 'product_review_facebook_ids';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['facebook_id', 'name'];

    public $primaryKey = 'facebook_id';

    /**
     * Get all of the tags for the post.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'product_review_group_facebook_id', 'facebook_id', 'group_id');
    }
}
