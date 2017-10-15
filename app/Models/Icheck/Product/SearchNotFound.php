<?php

 namespace App\Models\Icheck\Product;

 use Illuminate\Database\Eloquent\Model;

 class SearchNotFound extends Model
 {
     protected $connection = 'icheck_product';

     protected $table = 'search_notfound';

     public $timestamps = false;
     protected $fillable = ['gtin_code','time'];
 }


 ?>