<?php


namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'product_report';

    protected $fillable = [
        'gtin_code', 'icheck_id', 'report_type_id','note','status'
    ];

//    public $timestamps = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function product()
    {
        return $this->hasOne(Product::class, 'gtin_code', 'gtin_code');
    }

    public function reportType()
    {
        return $this->belongsTo(ReportType::class, 'report_type_id');
    }
}

?>