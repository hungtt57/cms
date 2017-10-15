<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Icheck\Product\Product as SProduct;
use App\Models\Enterprise\Product;
class CountProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'count:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $this->line('Hello!');
//        $countName = Product::where('product_name','!=','')->where('product_name','!=',null)->count();
//        $this->line('Product has full name: '.$countName);
//        $countImage  = Product::where('image_default','!=',null)->count();
//        $this->line('Product has full image: '.$countImage);
//        $countCategory  =  Product::with(['categories'])->has('categories', '<=', 0)->count();
//        $this->line('Product hasn"t category: '.$countCategory);
//
//
//        $countFUll = Product::where('product_name','!=','')->where('product_name','!=',null)->where('image_default','!=',null)->with(['categories'])->has('categories', '<=', 0)->count();
//        $this->line('Product has full option: '.$countFUll);
//        $this->line('END!!!');

            $products = Product::all();
        foreach ($products as $product){
            $this->line('SP : '.$product->barcode);
            $count = SProduct::where('gtin_code',$product->barcode)->count();

            if($count == 0){
                $product->delete();
                $this->line('DELETE :');
            }
        }
        $this->line('End');
    }
}
