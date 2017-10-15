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
use App\Models\Enterprise\CategoryData;
class GetInfoCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get info from GA';

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
        $categories = Category::where('parent_id',12)->get();
        if($categories){
            foreach ($categories as $category){
                $products = $category->products()->lists('gtin_code');

                $startDate = Carbon::now()->subDays(1)->startOfDay();

                $analytics = new AnalyticsLib();
                if($products){
                    try{
                        $chartData = null;

                        $chartData = $analytics->getDataCategory($startDate, $startDate, $products);

                        if($chartData){
                            $scan = (isset($chartData['pro_scan']) ? $chartData['pro_scan'] : 0);
                            $like = (isset($chartData['pro_like']) ? $chartData['pro_like'] : 0);
                            $comment = (isset($chartData['pro_comment']) ? $chartData['pro_comment'] : 0);
                            $new = CategoryData::firstOrCreate(['category_id' => $category->id,'date' =>$startDate->getTimeStamp()]);
                            $new->scan = $scan;
                            $new->like = $like;
                            $new->comment = $comment;
                            $new->save();
                        }

                    }catch(\Exception $ex){
                        continue;
                    }
                    $this->line('Success : ' . $category->id);
                }


            }
            $this->line('END');
        }
    }
}
