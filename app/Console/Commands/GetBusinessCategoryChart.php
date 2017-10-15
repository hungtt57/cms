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
class GetBusinessCategoryChart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get category business data from GA';

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
        $categories = Category::where('parent_id', 12)->get();
        if($categories){
            foreach ($categories as $category){
                $this->line('start : '. $category->id);
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
                        $products = $category->products()->whereIn('gtin_code',$array_gtin)->lists('gtin_code');

                        $startDate = Carbon::now()->subDays(1)->startOfDay();
                        $analytics = new AnalyticsLib();
                        if($products){
                            try{
                                $chartData = null;

                                $chartData = $analytics->getDataBusinessCategory($startDate, $startDate, $products);

                                if($chartData){
                                    $this->line('Success : ' . $category->id . ' with business : '.$business->id);
                                    $new = BusinessCategoryChart::firstOrCreate(['business_id' => $business->id,'category_id' => $category->id,'date' =>$startDate->getTimeStamp()]);
                                    $new->scan = $chartData['pro_scan'];
                                    $new->like = $chartData['pro_like'];
                                    $new->comment = $chartData['pro_comment'];
                                    $new->show = $chartData['pro_show'];
                                    $new->total = $chartData['pro_scan'] + $chartData['pro_like'] +  $chartData['pro_comment'] + $chartData['pro_show'];
                                    $new->save();

                                }

                            }catch(\Exception $ex){
                                $this->line('ERROR : ' . $category->id . ' with business : '.$business->id);
                               continue;
                            }

                        }

                    }
                }

            }
            $this->line('END');
        }
    }
}
