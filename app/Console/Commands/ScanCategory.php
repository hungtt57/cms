<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Jobs\ExportHCMJob;
use App\Models\Enterprise\GLN;
use App\Models\Icheck\Product\Product;
use App\GALib\AnalyticsLib;
use DB;
use App\Models\Enterprise\ProductDistributor;
use App\Models\Icheck\Product\CategoryProduct;
class ScanCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scan category';

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
        date_default_timezone_set('Asia/Saigon');
        $category_id = $this->ask('Enter category id (,):?');
        $category_id = explode(',',$category_id);
        $start_date = $this->ask('Enter start-date(dd-mm-yyyy) :?');
        $end_date = $this->ask('Enter end-date(dd-mm-yyyy) :?');
        $start_date = str_replace('/','-',$start_date);
        $end_date = str_replace('/','-',$end_date);

        $startDate = Carbon::createFromFormat('d-m-Y',$start_date)->startOfDay();
        $endDate = Carbon::createFromFormat('d-m-Y',$end_date)->startOfDay();


        $product_id = CategoryProduct::whereIn('category_id',$category_id)->distinct()->get()->lists('product_id');
        $gtin_codes = Product::whereIn('id',$product_id)->get()->lists('gtin_code');
        if($gtin_codes){
            $this->line('Start:'.Carbon::now()->toDateTimeString());
            $analytics = new AnalyticsLib();
            try{

                $info = $analytics->getInfoCategory($startDate, $endDate, $gtin_codes);
                if($info){
                    foreach ($info as $key => $i){
                        $this->line(Carbon::createFromTimeStamp($key)->format('d/m/Y').' : '.$i);
                    }
                }
            }catch(Exception $ex){

            }


            $this->line('END:'.Carbon::now()->toDateTimeString());
        }

    }
}
