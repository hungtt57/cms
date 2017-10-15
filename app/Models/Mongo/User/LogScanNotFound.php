<?php
namespace App\Models\Mongo\User;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class LogScanNotFound extends Model
{
    protected $connection = 'icheck_user_mongo';
    protected $collection = 'u_logscannotfound';
    /**
     * {@inheritdoc}
     */
//    const CREATED_AT = 'createdAt';
//    const UPDATED_AT = 'updatedAt';
//
//    protected $fillable = ['gtin_code', 'internal_code'];


}
?>
