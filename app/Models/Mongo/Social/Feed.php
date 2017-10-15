<?php

namespace App\Models\Mongo\Social;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Feed extends Model
{
    protected $connection = 'icheck_social_mongo';
    protected $collection = 's_feed';

     /**
      * {@inheritdoc}
      */
     const CREATED_AT = 'createdAt';

     /**
      * {@inheritdoc}
      */
     const UPDATED_AT = 'updatedAt';
}
