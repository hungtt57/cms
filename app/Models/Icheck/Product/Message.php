<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'message';


    protected $fillable = [
        'short_msg', 'full_msg', 'title','type'

    ];

    public $timestamps = false;
}



?>