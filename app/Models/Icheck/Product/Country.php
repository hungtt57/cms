<?php

 namespace App\Models\Icheck\Product;

 use Illuminate\Database\Eloquent\Model;

 class Country extends Model
 {
     protected $connection = 'icheck_product';

     protected $table = 'country';

        const CREATED_AT = 'createdAt';
        const UPDATED_AT = 'updatedAt';

 }


 ?>