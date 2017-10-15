<?php

namespace App\Models\Icheck\Product;

use Illuminate\Database\Eloquent\Model;

class ProductAttr extends Model
{
    protected $connection = 'icheck_product';

    protected $table = 'product_attr';
    protected $fillable = ['title', 'icon','show_block','owner','is_core'];

    public function getIconAttribute() {

        if ( str_contains( $this->attributes['icon'], 'http' ) ) {
            return $this->attributes['icon'];
        }
        $sizes = ["original", "thumb_small", "thumb_medium", "thumb_large", "small", "medium", "large"];

        return 'http://ucontent.icheck.vn/' . $this->attributes['icon'] . '_' . 'thumb_small' . '.jpg';


    }
}



?>