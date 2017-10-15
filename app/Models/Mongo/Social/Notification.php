<?php

namespace App\Models\Mongo\Social;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Notification extends Model
{
    protected $connection = 'icheck_social_mongo';
    protected $collection = 's_notifications';
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';
    const ACTIVITY_TYPE_FOLLOW_USER = 1;
    const ACTIVITY_TYPE_UNFOLLOW_USER= 2;
    const ACTIVITY_TYPE_CREATE_POST= 3;
    const ACTIVITY_TYPE_REVIEW_PRODUCT = 4;
    const ACTIVITY_TYPE_SHARE_PRODUCT = 5;
    const ACTIVITY_TYPE_SHARE_POST = 6;
    const ACTIVITY_TYPE_SHARE_LINK = 7;
    const ACTIVITY_TYPE_POST_TO_GROUP = 8;
    const ACTIVITY_TYPE_SHARE_PRODUCT_TO_GROUP = 9;
    const ACTIVITY_TYPE_SHARE_POST_TO_GROUP = 10;
    const ACTIVITY_TYPE_SHARE_LINK_TO_GROUP = 11;
    const ACTIVITY_TYPE_POST_TO_USER_WALL = 12;
    const ACTIVITY_TYPE_SHARE_PRODUCT_TO_USER_WALL = 13;
    const ACTIVITY_TYPE_SHARE_POST_TO_USER_WALL = 14;
    const ACTIVITY_TYPE_SHARE_LINK_TO_USER_WALL = 15;
    const ACTIVITY_TYPE_LIKE_PRODUCT = 100;
    const ACTIVITY_TYPE_UNLIKE_PRODUCT = 101;

    const OBJECT_TYPE_PRODUCT = 1;
    const OBJECT_TYPE_USER = 2;
    const OBJECT_TYPE_POST = 3;
    const OBJECT_TYPE_GROUP = 4;

    //notify_type
    const activity= 1;
    const system=2;

    //Nếu là activity thì là {}, nếu là system thì là {message: “…”}




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
