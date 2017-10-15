<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\GALib\AnalyticsLib;
use DB;
use App\Models\Mongo\User\LogScanNotFound;
use App\Models\Enterprise\GtinLogScanNotFound;
use App\Models\Icheck\Product\Product;
class CheckLogScanNotFound extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:logscannotfound';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'kiem tra ma log scan la gtin';

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
        LogScanNotFound::chunk(1000,function ($codes){
            foreach ($codes as $code){
                $c = trim($code->id);
                if(validate_barcode($c)){
                    $count = Product::where('gtin_code','like','%'.$c.'%')->count();
                    if($count <= 0 ){
                        $m = GtinLogScanNotFound::firstOrCreate(['gtin_code' => $c]);
                        $m->score = $code->score;
                        $m->status = 0;
                        $m->save();
                        $this->line($c);
                    }

                }
            }
        });


    }

}
