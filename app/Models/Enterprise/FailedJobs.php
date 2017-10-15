<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use MongoDB\BSON\ObjectID;
use App\Models\Icheck\Social\Post as SPost;
class FailedJobs extends Model
{
    use HybridRelations;

    protected $table = 'failed_jobs';

    

}
