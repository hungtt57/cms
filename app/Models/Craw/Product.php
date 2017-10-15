<?php

namespace App\Models\Craw;

use Jenssegers\Mongodb\Eloquent\Model as Model;
class Product extends Model
{
    protected $connection = 'craw_mongodb';
    protected $collection = 'crawler-datas';
    protected $fillable = [
      'url','text','price','keywords','description','siteId','parameters','images'
    ];
    public $timestamps = false;

}
