<?php namespace App\Listeners;

use App\Models\BarcodeViet\MSMVGTIN;
use App\Models\Icheck\Product\Product;
use App\Models\Icheck\Product\ProductInfo;
use Illuminate\Support\Facades\Log;
use App\Models\Enterprise\Product as ProductDN;
class ProductSaved {

    protected $product = null;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(Product $product)
    {
            try{
                $msmv_gtins = MSMVGTIN::where('gtin_code', $product->gtin_code)->first();

                if (empty($msmv_gtins)) {
                    $msmv_gtins = new MSMVGTIN();
                    $msmv_gtins->gtin_code = $product->gtin_code;
                }

                $info = ProductInfo::where('product_id', $product->id)->where('attribute_id', 1)->first();
                $msmv_gtins->product_name = $product->product_name;
                if ($product->image_default != null) {
                    $msmv_gtins->product_image_url = $product->image();
                }else{
                    $msmv_gtins->product_image_url = null;
                }
                $msmv_gtins->product_price = $product->price_default;
                if ($info) {
                    $msmv_gtins->product_description = $info->content;
                }else{
                    $msmv_gtins->product_description = null;
                }
                $vendor = $product->vendor;

                if ($vendor) {
                    $msmv_gtins->company_name = $vendor->name;

                    if($vendor->address){
                        $msmv_gtins->company_address = $vendor->address;
                    }
                    if($vendor->phone){
                        $msmv_gtins->company_contact = $vendor->phone;
                    }
                    if($vendor->gln_code){
                        $msmv_gtins->gln_code = $vendor->gln_code;
                    }
                    if(isset($vendor->country)){
                        $msmv_gtins->country = $vendor->country->name;
                    }


                }

                $msmv_gtins->save();

                $productDN = ProductDN::where('barcode',$product->gtin_code)->get();
                if($productDN){
                    foreach ($productDN as $p){
                        $p->name = $product->product_name;
                        $p->save();
                    }
                }
            }catch(\Exception $ex){

            }


    }

    // Other Handlers/Methods...
}