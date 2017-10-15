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
use App\Models\Enterprise\CategoryChart;
use App\Models\Icheck\Product\Category;
class GetCategoryChart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:chart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from GA';

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
        $categories = Category::where('parent_id', 12)->get();
        if($categories){
            foreach ($categories as $category){
                $this->line('start : '. $category->id);
                $products = $category->products()->lists('gtin_code');


                $startDate = Carbon::now()->subDays(1)->startOfDay();

                $analytics = new AnalyticsLib();
                if($products){
                    try{
                        $chartData = null;

                        $chartData = $analytics->getDataByCronJob($startDate, $startDate, $products);

                        if($chartData){
                            $new = CategoryChart::firstOrCreate(['category_id' => $category->id,'date' =>$startDate->getTimeStamp()]);
                            $new->total = $chartData;
                            $new->save();
                        }

                    }catch(\Exception $ex){
                        $this->line('Error : '.$ex->getMessage() . $category->id);

                    }
                    $this->line('Success : ' . $category->id);
                }


            }
            $this->line('END');
        }
    }
}
