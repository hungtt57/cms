<?php

namespace App\Models\Icheck\Social;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'icheck_social';

    protected $table = 's_category';

    protected $fillable = [
        'name', 'description', 'settings','keywords'
    ];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function posts()
    {
        return $this->belongsToMany(Category::class, 's_category_post');
    }
}
