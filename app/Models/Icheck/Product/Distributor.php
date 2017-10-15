<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;
use App\Models\Icheck\Product\Country;
use App\Models\Icheck\Product\Product;
class Distributor extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'distributor';

    protected $fillable = [
        'name', 'address', 'country',
        'contact', 'site', 'other','title_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country');
    }
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * Get all of the tags for the post.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'distributor_product', 'distributor_id', 'product_id');
    }

    public function title(){
        return $this->belongsTo(DistributorTitle::class,'title_id');
    }
}
