<?php


namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'report_type';

    protected $fillable = [
        'name'
    ];

}

