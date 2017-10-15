<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;


class AttrDynamic extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'attr_dynamic';

    protected $fillable = [
        'title', 'key', 'enum',
        'type'
    ];
   public $timestamps = false;
}
