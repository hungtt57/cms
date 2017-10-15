<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Models\Icheck\Product\Product;
use App\GALib\AnalyticsLib;
use DB;
use App\Models\Collaborator\SearchResult;
use App\Models\Enterprise\GtinLogScanNotFound;
use App\Models\Enterprise\LowQualityProduct;
use GuzzleHttp\Client;
use App\Models\Collaborator\ContributeProduct;
class ScanProductBing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:bing';

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
        Product::where('product_name','')->where('searched',0)->chunk(1000,function ($products) {

            foreach ($products as $product) {

                $this->line($product->gtin_code);
                $count = ContributeProduct::where('gtin', $product->gtin_code)->count();
                if ($count > 0) {
                    $this->line('Ton tai');
                    $product->searched = 3;
                    $product->save();
                    continue;

                };
                $searchResults = [];
                $client = new Client();
                $key = 'e49ce7cf78d446e49a19f6263dd1ebc4';
                $api = 'https://api.cognitive.microsoft.com/bing/v5.0/search?q=' . $product->gtin_code . '&count=10&offset=0&mkt=en-us&safesearch=Off';
                try {
                    $res = $client->request(
                        'GET',
                        $api,
                        [
                            'headers' => [
                                'Ocp-Apim-Subscription-Key' => $key,
                            ],
                        ]
                    );

                    $res = json_decode((string)$res->getBody());

                    if (isset($res->webPages->value)) {
                        foreach ($res->webPages->value as $re) {
                            $searchResults[] = [
                                'name' => $re->name,
                                'url' => $re->url,
                            ];
                        }
                    }
                } catch (RequestException $e) {
                    $searchResults = [];
                }

                $cacheSearchResult = SearchResult::firstOrCreate([
                    'gtin' => $product->gtin_code,
                    'results' => $searchResults,
                ]);
                if (!$searchResults) {
                    // ko co ket qua bing
                    $this->line('Khong co ket qua');
                    $product->searched = 2;
                    $product->save();
//                    $low = LowQualityProduct::where('gtin_code', $product->gtin_code)->first();
//                    if ($low) {

//                        LowQualityProduct::where('gtin_code', $product->gtin_code)->update(['found' => 0]);
//                    }

                } else {
                    $this->line('Co ket qua');
                    $product->searched = 1;
                    $product->save();
                }
                $this->line('End:'.$product->gtin_code);
            }
        });
    }
}
