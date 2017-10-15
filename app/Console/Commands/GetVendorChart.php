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
use App\Models\Icheck\Product\Vendor;
use App\Models\Enterprise\VendorChart;
class GetVendorChart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:chart';

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
        ini_set('memory_limit',-1);
        date_default_timezone_set('Asia/Saigon');
        $this->line(Carbon::now()->toDateTimeString());
        $vendors = Vendor::all();
        if($vendors){
            foreach ($vendors as $vendor){
                $gtin_codes = $vendor->products()->lists('gtin_code')->toArray();
                $startDate = Carbon::now()->subDays(1)->startOfDay();
                $analytics = new AnalyticsLib();
                if($gtin_codes){
                    try{
                        $chartData = null;

                        $chartData = $analytics->getDataByCronJob($startDate, $startDate, $gtin_codes);

                        if($chartData){
                            $this->line('Success : ' . $vendor->id);
                            $new = VendorChart::firstOrCreate(['vendor_id' => $vendor->id,'date' =>$startDate->getTimeStamp()]);
                            $new->total = $chartData;
                            $new->save();
                        }

                    }catch(Exception $ex){
                        $this->line('Error : ' . $vendor->id);
                        continue;
                    }
                }


            }
        }

    }
}
