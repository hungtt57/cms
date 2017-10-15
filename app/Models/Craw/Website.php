<?php

namespace App\Models\Craw;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Icheck\User\Account;
use App\Models\Icheck\Product\Product;
class Website extends Model
{
    protected $connection = 'craw_mongodb';
    protected $collection = 'websites';
    protected $fillable = [
        'name', 'url', 'acceptedRegex','ignoredRegex','detailRegex',
        'delayTime','xpathName','xpathPrice','xpathImage','xpathKeyword',
        'xpathDescription','xpathParameter','isActive','xpathBarCode',
        'xpathUrl','status'
    ];
    public $timestamps = false;

}
