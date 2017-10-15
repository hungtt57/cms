<?php

namespace App\Models\Mongo\Social;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class StaffNotification extends Model
{
    protected $connection = 'icheck_social_mongo';
    protected $collection = 's_staff_notifications';
    /**
     * {@inheritdoc}
     */
    protected $dateFormat = DATE_ISO8601;

    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';

    const TYPE_BUSINESS_REGISTERED = 1;
    const TYPE_BUSINESS_ADD_GLN = 2;
    const TYPE_BUSINESS_UPDATE_GLN = 3;
    const TYPE_BUSINESS_DELETE_GLN = 4;
    const TYPE_BUSINESS_ADD_PRODUCT = 5;
    const TYPE_BUSINESS_UPDATE_PRODUCT = 6;
    const TYPE_BUSINESS_DELETE_PRODUCT = 7;
    const TYPE_IMPORT_PRODUCT_FAILED = 51;
}
