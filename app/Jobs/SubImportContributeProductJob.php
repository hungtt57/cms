<?php

namespace App\Jobs;

use DB;
use App\Jobs\Job;
use App\Models\Icheck\Product\SearchNotFound;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Collaborator\ContributeProduct as Product;
use App\Models\Collaborator\SearchResult;
//use App\Models\Social\Product as SProduct;
use GuzzleHttp\Client;
use App\Models\Icheck\Product\Product as SProduct;
use App\Models\Icheck\Product\Vendor as SocialVendor;
use Carbon\Carbon;
use App\Models\Enterprise\GtinLogScanNotFound;
use App\Models\Enterprise\LowQualityProduct;
use Illuminate\Support\Facades\Log;
//extends Job implements ShouldQueue
class SubImportContributeProductJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $gtin;
    protected $group;
    protected $type;
    public $jobName;
    public $createBy;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gtin,$type,$group,$createBy)
    {
        $this->gtin = $gtin;
        $this->group = $group;
        $this->type = $type;
        $this->jobName = 'Sub con Import đóng góp sản phẩm ctv';
        $this->createBy = $createBy;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        ini_set('memory_limit', '2048M');
        $gtin = $this->gtin;
        $group = $this->group;
        $type = $this->type;
        foreach ($gtin as $number) {
            LowQualityProduct::where('gtin_code', $number)->delete();

            if ($type == 2) {
                GtinLogScanNotFound::where('gtin_code', $number)->update(['status' => 1]);
            }


            $products = Product::where('gtin', $number)->get();
            $p = SProduct::where('gtin_code', $number)->first();

            $count = SProduct::where('gtin_code', $number)->has('categories', '>', 0)->where('product_name','!=',null)->count();
            if($count > 0){
                continue;
            }
            if (($cacheSearchResult = SearchResult::where('gtin', $number)->first())) {
            } else {
                $searchResults = [];
                if ($type == 1 or $type == 2 or $type == 6) {
                    $client = new Client();
                    $key = 'e49ce7cf78d446e49a19f6263dd1ebc4';
                    $api = 'https://api.cognitive.microsoft.com/bing/v5.0/search?q=' . $number . '&count=10&offset=0&mkt=en-us&safesearch=Off';
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
                }

                $cacheSearchResult = SearchResult::create([
                    'gtin' => $number,
                    'results' => $searchResults,
                ]);
            }
            if ($type == 1 or $type == 2 or $type == 6) {
                if (!$cacheSearchResult->results) {
                    continue;
                }
            }
            $product = null;
            if ($products->count() <= 0) {
                $data = [
                    'gtin' => $number,
                    'status' => Product::STATUS_AVAILABLE_CONTRIBUTE,
                    'searchResults' => $cacheSearchResult->results,
                ];
                if ($p) {
                    if ($p->product_name) {
                        $data['name'] = $p->product_name;
                    }
                    if ($p->price_default) {
                        $data['price'] = $p->price_default;
                    }

                }

                if ($group) {
                    $data['group'] = $group;
                }

                $product = Product::create($data);

                if ($p) {
                    if ($type != 7) {
                        if ($p->image_default) {
                            $product->push('images', [
                                'path' => $p->image_default,
                                'default' => false
                            ]);
                        }
                    }

                    if ($p->categories) {
                        foreach ($p->categories as $category) {
                            $product->push('categories', [
                                'id' => $category->id,
                                'name' => $category->name,
                                'level' => 0
                            ]);
                        }
                    }
                }


            } else {
                $new = true;

                foreach ($products as $product) {
                    if (in_array($product->status, [Product::STATUS_PENDING_APPROVAL, Product::STATUS_IN_PROGRESS, Product::STATUS_AVAILABLE_CONTRIBUTE,Product::STATUS_DISAPPROVED])) {
                        $new = false;
                    }
                }

                if ($new) {
                    $data = [
                        'gtin' => $number,
                        'status' => Product::STATUS_AVAILABLE_CONTRIBUTE,
                        'searchResults' => $cacheSearchResult->results,
                    ];
                    if ($p) {
                        if ($p->product_name) {
                            $data['name'] = $p->product_name;
                        }
                        if ($p->price_default) {
                            $data['price'] = $p->price_default;
                        }

                    }
                    if ($group) {
                        $data['group'] = $group;
                    }

                    $product = Product::create($data);

                    if ($p) {
                        if ($type != 7) {
                            if ($p->image_default) {
                                $product->push('images', [
                                    'path' => $p->image_default,
                                    'default' => false
                                ]);
                            }
                        }
                        if ($p->categories) {
                            foreach ($p->categories as $category) {
                                $product->push('categories', [
                                    'id' => $category->id,
                                    'name' => $category->name,
                                    'level' => 0
                                ]);
                            }
                        }
                    }
                }
            }
            if($product and $p){
                $p->searched = 3;
                $p->save();
            }

        }
    }
}
