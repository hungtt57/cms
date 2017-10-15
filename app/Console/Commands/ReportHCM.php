<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enterprise\MICheckReport;
use Carbon\Carbon;
use App\Jobs\ReportData;
use App\Models\Icheck\Product\Vendor;
use Mail;
use App\Models\Collaborator\ContributeProduct;
use App\Models\Icheck\Product\Product;
class ReportHCM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report-hcm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        try{
            $fp = fopen(storage_path('app/vendor-not-prefix.csv'), 'w');
            Vendor::where('prefix',null)->chunk(5000,function($vendors) use ($fp){
                foreach ($vendors as $vendor){
                    fputcsv($fp,[$vendor->gln_code]);
                }
            });
            fclose($fp);
            Mail::raw('', function ($message) {
                $message->from('business@icheck.vn', 'iCheck cho doanh nghiệp');
                $message->to('huongcm@icheck.vn', 'Chu Minh Huong');
                $message->cc('hunguet1471994@gmail.com', 'Trương Tiến Hưng');
                $message->subject('Vendor ko co prefix ');
                $message->attach(storage_path('app/vendor-not-prefix.csv'), []);
            });

            $fp = fopen(storage_path('app/ctv-not-vendor.csv'), 'w');
            ContributeProduct::where('status',ContributeProduct::STATUS_APPROVED)->chunk(5000,function($products) use ($fp){
                foreach ($products as $product){
                        $c = Product::where('gtin_code',$product->gtin)->where('vendor_id',null)->count();
                    if($c ){
                        fputcsv($fp,[$product->gtin]);
                    }

                }
            });
            fclose($fp);
            Mail::raw('', function ($message) {
                $message->from('business@icheck.vn', 'iCheck cho doanh nghiệp');
                $message->to('huongcm@icheck.vn', 'Chu Minh Huong');
                $message->cc('hunguet1471994@gmail.com', 'Trương Tiến Hưng');
                $message->subject('Vendor ko co prefix ');
                $message->attach(storage_path('app/ctv-not-vendor.csv'), []);
            });



            $this->line('Da gui mail xong!!');




        }catch(\Exception $ex){

        }
    }
}
