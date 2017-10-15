<?php

namespace App\Models\Icheck\Social;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $connection = 'icheck_social';

    protected $table = 's_news';
    public $timestamps = false;
}
