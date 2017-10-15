<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'currency';


    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
}



?>