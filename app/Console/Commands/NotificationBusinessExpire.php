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
use App\Models\Enterprise\Business;
class NotificationBusinessExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'thong bao ngay het han cho doanh nghiep';

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
        $today = Carbon::now()->startOfDay();
        //set het han hop dong ve 0
        DB::beginTransaction();
        try{
            Business::where('end_date','<=',$today)->update(['start_date' => '','end_date' => '']);
            $business = Business::where('start_date','0000-00-00 00:00:00')->orWhere('end_date','0000-00-00 00:00:00')->get();
            if($business){
                foreach ($business as $b){
                    $b->roles()->sync([]);
                }
            }
            DB::commit();
        }catch(\Exception $ex){
            DB::rollback();
        }





    }
}
