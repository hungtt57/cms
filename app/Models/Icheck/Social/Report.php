<?php

namespace App\Models\Icheck\Social;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $connection = 'icheck_social';

    protected $table = 's_report';

    protected $fillable = [
        'status', 'icheck_id', 'type_id','note','status'
    ];
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function reportType()
    {
        return $this->belongsTo(ReportType::class, 'type_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'object_id');
    }
}
