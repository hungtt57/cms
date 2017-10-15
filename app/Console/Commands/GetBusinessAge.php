<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
//use App\Models\Icheck\Product\Product;
use App\GALib\AnalyticsLib;
use DB;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\Product;
use App\Models\Enterprise\BusinessChart;
use App\Models\Enterprise\BusinessCategoryChart;
use App\Models\Icheck\Product\Category;
use App\Models\Enterprise\BusinessAge;
class GetBusinessAge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:age';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get age business data from GA';

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
        $this->line(Carbon::now()->toDateTimeString());
        $businesses = Business::where('status',Business::STATUS_ACTIVATED)->get();
        if($businesses){
            foreach ($businesses as $business){

                $gln = $business->gln()->where('status', GLN::STATUS_APPROVED)->get();
                $gln = $gln->lists('id')->toArray();
                $productsSx = Product::whereIn('gln_id', $gln)->where('status',Product::STATUS_APPROVED);
                $productPp = $business->productsDistributor();

                $gtin_sx = $productsSx->lists('barcode')->toArray();

                $gtin_pp = $productPp->lists('gtin_code')->toArray();
                $array_gtin = array_merge($gtin_sx, $gtin_pp);
                $startDate = Carbon::now()->subDays(1)->startOfDay();
                $analytics = new AnalyticsLib();
                if($array_gtin){
                    try{
                        $chartData = null;

                        $chartData = $analytics->getBusinessAge($startDate, $startDate, $array_gtin);

                        if($chartData){
                            $new = BusinessAge::firstOrCreate(['business_id' => $business->id,'date' =>$startDate->getTimeStamp()]);
                            $new->update($chartData);
                            $new->save();
                            $this->line('Success : ' . $business->id);
                        }

                    }catch(Exception $ex){
                        continue;
                    }

                }


            }
            $this->line('END');
        }
    }
}
