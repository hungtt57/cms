<?php

namespace App\Models\Mongo\Social;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Icheck\User\Account;
class Comment extends Model
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
    public function account(){
        $icheck_id = $this->attributes['owner']['icheck_id'];
        $account = Account::where('icheck_id',$icheck_id)->first();
        return $account;
    }

    public function childs()
    {

        $comment = Comment::where('parent',$this->attributes['_id'])->where('deleted_at',null)->orderBy('createdAt','desc')->get();
        return $comment;

    }
}
