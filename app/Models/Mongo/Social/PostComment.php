<?php

namespace App\Models\Mongo\Social;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class PostComment extends Model
{
    protected $connection = 'icheck_social_mongo';
    protected $collection = 's_comment';
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';

//    public function childs()
//    {
//        return $this->hasMany(MComment::class, 'parent', 'objectId');
//    }
//
//    public function getObjectIdAttribute($value)
//    {
//        return $this->attributes['_id'];
//    }
}
