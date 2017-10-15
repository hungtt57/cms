<?php


namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;

class ProductMessage extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'product_message';

    protected $fillable = [
        'gtin_code', 'gln_code', 'message_id',
    ];

//    public $timestamps = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function product()
    {
        return $this->hasOne(Product::class, 'gtin_code', 'gtin_code');
    }
}

?>