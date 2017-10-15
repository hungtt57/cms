<?php

namespace App\Models\Icheck\Social;

use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{
    protected $connection = 'icheck_social';

    protected $table = 's_report_type';

    protected $fillable = [
        'name'
    ];

}
