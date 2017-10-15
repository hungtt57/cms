<?php

namespace App\Models\Enterprise;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class MNotification extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications';
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';
}
