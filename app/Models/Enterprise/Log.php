<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use App\Models\Social\MFeed;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use MongoDB\BSON\ObjectID;

class Log extends Model
{
    use HybridRelations;

    protected $table = 'logs';

    protected $fillable = [
        'email', 'action',

    ];
}
