<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use Carbon\Carbon;
use App\Models\Icheck\Product\Product;
use Illuminate\Support\Facades\Log;
class UpdateAttrProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:attr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật lại feature theo thuộc tính của sản phẩm';

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
        Product::has('properties','>',0)->chunk(1000,function($products){
            foreach ($products as $product){
                try{
                    $properties = $product->properties()->get()->lists('content')->toArray();
                    if($properties){
                        $properties = array_slice($properties, 0, 3);
                        $f = implode(',',$properties);
                        $product->features = $f;
                        $product->save();
                        $this->line('Success : '.$product->gtin_code);
                    }
                }catch(\Exception $ex){
                    Log::error('Job cập nhật thuộc tính lỗi gtin_code: '.$product->gtin_code);
                    continue;
                }

            }
        });

    }
}
