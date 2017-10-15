<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use MongoDB\BSON\ObjectID;
use App\Models\Icheck\Social\Post as SPost;
use App\Models\Icheck\Social\Category;
class Post extends Model
{
    use HybridRelations;

    protected $table = 'posts';

    protected $fillable = [
        'title', 'description', 'content',
        'image', 'source', 'tag', 'version',
        'publishBy','publishTime'
    ];

//    public function feed()
//    {
//        return $this->hasOne(Feed::class, '_id', 'icheck_id');
//    }
    public function postIcheck()
    {
        return $this->hasOne(SPost::class,'id','icheck_id');
    }

}
