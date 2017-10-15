<?php
namespace App\Models\Mongo\Product;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Mongo\Product\PComment;
class PProduct extends Model
 {
     protected $connection = 'icheck_product_mongo';
     protected $collection = 'p_product';
     /**
      * {@inheritdoc}
      */
     const CREATED_AT = 'createdAt';

     /**
      * {@inheritdoc}
      */
     const UPDATED_AT = 'updatedAt';

     protected $fillable = ['gtin_code', 'internal_code'];


 }
 ?>
