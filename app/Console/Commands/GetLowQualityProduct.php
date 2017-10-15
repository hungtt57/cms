<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Models\Icheck\Product\Product;
use App\GALib\AnalyticsLib;
use DB;
use App\Models\Enterprise\LowQualityProduct;
class GetLowQualityProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'low:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'lay ra sp co anh kem chat luong';

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
        $date = Carbon::now()->startOfDay();
           Product::where('image_default','!=','')->chunk(5000,function ($products){
                foreach ($products as $product){
                    $image = trim(get_image_url($product->image_default));
                    $file_size = $this->retrieve_remote_file_size($image);
                    try{
                        if(0 < $file_size and $file_size < 20480){
                            $p = LowQualityProduct::firstOrCreate(['gtin_code' => $product->gtin_code]);
                            $p->scan = $product->scan_count;
                            $p->save();
                            $this->line($product->gtin_code.' scan : '.$product->scan_count);
                        }else{
                            $low = LowQualityProduct::where('gtin_code',$product->gtin_code)->first();
                            if($low){
                                $low->delete();
                            }
                        }
                    }catch(\Exception $ex){
                        continue;
                    }


                }
            });

            $this->line('END');

    }
    function retrieve_remote_file_size($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        return intval($size);
    }
}
