<?php

namespace App\Models\Icheck\Social;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $connection = 'icheck_social';

    protected $table = 's_category_post';

    protected $fillable = [
        'post_id', 'category_id'
    ];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

}
