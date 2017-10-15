<?php

namespace App\Models\Enterprise;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class MLog extends Model
{
    //Fix name
    protected $connection = 'mongodb';
    protected $collection = 'logs';
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'email', 'action',

    ];
}
