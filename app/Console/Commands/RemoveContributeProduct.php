<?php

namespace App\Console\Commands;

use App\Models\Collaborator\ContributeProduct;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;

use App\Models\Icheck\Product\Product;
class RemoveContributeProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:contribute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert conmand';

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
//        ContributeProduct::where('status',ContributeProduct::STATUS_PENDING_APPROVAL)->chunk(5000,function($contributes){
//            try{
//                foreach ($contributes as $contribute){
//                    $count = Product::where('gtin_code',$contribute->gtin)->where('product_name','!=',null)->count();
//                    if($count > 0){
//                        $contribute->delete();
//                        $this->line('Delete : '.$contribute->gtin);
//                    }
//                }
//            }catch(\Exception $ex){
//            }
//
//        });
//        $this->line('end');
        ContributeProduct::where('status',ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE)->where('group','CTV')->chunk(5000,function($contributes){
            try{
                foreach ($contributes as $contribute){
                    $count = Product::where('gtin_code',$contribute->gtin)->where('product_name','!=',null)->count();
                    if($count > 0){
                        $contribute->delete();
                        $this->line('Delete : '.$contribute->gtin);
                    }
                }
            }catch(\Exception $ex){
            }

        });
        $this->line('end');
    }
}
