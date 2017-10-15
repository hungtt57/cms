<?php

namespace App\Models\Icheck\Social;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $connection = 'icheck_social';

    protected $table = 's_posts';
}
