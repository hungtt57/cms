<?php

namespace App\Models\Enterprise;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Social\MFeed;
use App\Models\Social\MAccount;

class MICheckReport extends Model
{
    const TARGET_TYPE_PRODUCT = 1;
    const TARGET_TYPE_FEED = 2;
    const TARGET_TYPE_USER = 3;

    const STATUS_PENDING = 0;
    const STATUS_RESOLVED = 1;

    public static $targetTypeTexts = [
        self::TARGET_TYPE_PRODUCT => 'product',
        self::TARGET_TYPE_FEED => 'feed',
        self::TARGET_TYPE_USER => 'user',
    ];

    public static $statusTexts = [
        self::STATUS_PENDING => 'pending',
        self::STATUS_RESOLVED => 'resolved',
    ];

    //Fix name
    protected $connection = 'mongodb';
    protected $collection = 'icheck_reports';
    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'createdAt';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'updatedAt';

    public function feed()
    {
        return $this->hasOne(MFeed::class, '_id', 'target');
    }

    public function account()
    {
        return $this->hasOne(MAccount::class, 'icheck_id', 'target');
    }
}
