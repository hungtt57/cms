<?php

namespace App\Listeners;


use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Excel;
use App\Models\Icheck\Product\Product;
use App\Models\Icheck\Product\Vendor;
use GuzzleHttp\Exception\RequestException;
use App\Models\Enterprise\MStaffNotification;
use App\Models\Collaborator\ContributeProduct;
use Illuminate\Support\Facades\Log;
use App\Models\Mongo\Product\PProduct;
use App\Events\ImportFileManagerUser;
use App\Models\Icheck\Product\ProductInfo;
class ListenImportFileManagerUser implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function handle(ImportFileManagerUser $event)
    {

        ini_set('memory_limit', '2048M');
        $icheck_id = $event->icheck_id;
        $filepath = $event->filepath;
        $originalFileName = $event->originalFileName;

        $data = Excel::selectSheetsByIndex(0)->load($filepath, function ($reader) {
            $reader->noHeading();
        })->skip(1)->get();

        $f = [];
        $f2 = [];
        $product_shop  = [];
        foreach ($data as $key => $row) {
            if (!$row[0]) {
                continue;
            }
            if (!validate_barcode(trim($row[0]))) {
                $f[] = $row[0];

                continue;
            }

            if (trim($row[5]) and !($gln = Vendor::where('gln_code', trim($row[5]))->first())) {
                $f2[] = $row[0];

                continue;
            }


            $product = Product::firstOrCreate(['gtin_code' => trim($row[0])]);

            if (!is_null($product)) {
                $add = false;
                $data = [];

                if (!$product->internal_code) {
                    $add = true;
                    if (!isset($gln)) {
                        $f2[] = $row[0];
                        $product->delete();

                        continue;
                    }

                    $data['internal_code'] = 'ip_' . microtime(true) * 10000;
                }

                if(isset($row[1])){
                    if (!is_null($row[1])) {
                        $data['product_name'] = $row[1];
                        $product->product_name = $data['product_name'];
                    }
                }

                if(isset($row[3])) {
                    if (!is_null($row[3])) {
                        $data['price_default'] = $row[3];
                        $product->price_default = $data['price_default'];
                    }
                }
                if(isset($row[2])) {
                    if (!is_null($row[2]) and ($image = @file_get_contents($row[2]))) {
                        $client = new \GuzzleHttp\Client();

                        try {
                            $res = $client->request(
                                'POST',
                                'http://upload.icheck.vn/v1/images?uploadType=simple',
                                [
                                    'body' => $image,
                                ]
                            );
                            $res = json_decode((string)$res->getBody());
                            $data['image_default'] = $res->prefix;
                            $product->image_default = $data['image_default'];
                        } catch (RequestException $e) {
                        }
                    }
                }
                $product_image = array();

                for ($i = 10; $i < 13; $i++) {
                    if(isset($row[$i])) {


                        if (!is_null($row[$i]) and ($image = @file_get_contents($row[$i]))) {
                            $client = new \GuzzleHttp\Client();

                            try {
                                $res = $client->request(
                                    'POST',
                                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                                    [
                                        'body' => $image,
                                    ]
                                );
                                $res = json_decode((string)$res->getBody());
                                $product_image[] = $res->prefix;
                            } catch (RequestException $e) {

                            }
                        }
                    }
                }

                $product->save();

                if(isset($row[4])) {
                    if (!is_null($row[4]) and $row[4] !== '') {
                        $categories = explode('|', $row[4]);
                        $product->categories()->sync($categories);
                    }
                }

                if (isset($gln)) {
                    $product->vendor_id = $gln->id;
                    $product->save();
                }

                $product->fresh();

                for ($i = 1; $i < 5; $i++) {
                    $col = $i + 5;

                    if (isset($row[$col]) && !empty(trim($row[$col]))) {
                        $count = ProductInfo::where('attribute_id',$i)->where('product_id',$product->id)->count();
                        if($count > 0){
                            $product->attributes()->updateExistingPivot($i, ['content' => nl2br($row[$col]),'content_text' => strip_tags($row[$col])]);
                        }else{
                            $info = new ProductInfo;
                            $info->product_id = $product->id;
                            $info->attribute_id = $i;
                            $info->content = nl2br($row[$col]);
                            $info->content_text =strip_tags($row[$col]);
                            $info->save();
                        }

                    }


                }

//                 Push anh len product mongo

                if (isset($product_image) && count($product_image) > 0) {

                    $pproduct = PProduct::where('gtin_code',$product->gtin_code)->first();

                    if($pproduct==null){
                        $pproduct = new PProduct();
                        $pproduct->gtin_code = $product->gtin_code;
                        $pproduct->save();
                    }

                    foreach ($product_image as $image) {
                        $pproduct->push('attachments', [
                            'type' => 'image',
                            'link' => $image,
                        ]);
                    }
                }

//                Call api dong bo redis
                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request('PUT', env('DOMAIN_API') . 'products/' . $product->id, [
                        'auth' => [env('USER_API'), env('PASS_API')],
                    ]);
                    $res = json_decode((string)$res->getBody());

                    if ($res->status != 200) Log::info('Loi cap nhat redis product:  ' . $product->id);
                } catch (RequestException $e) {
                    Log::info('Loi cap nhat redis product:  ' . $product->id);
                }


                    \App\Models\Enterprise\MLog::create([
                        'email' => $event->email,
                        'action' => 'Import sản phẩm vào shop' . $product->product_name . '(' . $product->gtin_code . ') bằng file('.$icheck_id.')',
                    ]);
                $product_shop[$key]['gtin_code'] = $product->gtin_code;
                $product_shop[$key]['price'] = $product->price_default;
                $product_shop[$key]['weight'] =(isset($row['13']))?$row['13']: 2;
                $product_shop[$key]['quantity'] = (isset($row['14']))?$row['14']: 1;
                $product_shop[$key]['condition_id'] = (isset($row['15'])) ? $row['15']:null;
                $product_shop[$key]['currency'] = (isset($row['16'])) ? $row['16']:null;
                $attachments = [];
                if(isset($data['image_default'])){
                    $attachments[] = [
                        'type' => 'image',
                        'link' => $data['image_default']
                    ];
                }
                if(count($product_image) > 0 ){
                    foreach ($product_image as $image){
                        $attachments[] = [
                            'type' => 'image',
                            'link' => $image
                        ];
                    }
                }
                $product_shop[$key]['attachments'] = $attachments;
            }
        }

        if(count($product_shop) > 0){

            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('POST','https://c2c.icheck.com.vn/shops/'.$icheck_id.'/products', [
                    'auth' => ['icheck','iYAF&;cBe#G3a~D:#heck'],
                    'form_params' => [
                        'products' => $product_shop,
                    ]
                ]);
                $res = json_decode((string)$res->getBody());

                if ($res->status != 200)  Log::info('Loi call API đăng bán sp lên c2c :  ' . $icheck_id);
            } catch (RequestException $e) {
                Log::info('Loi call API đăng bán sp lên c2c :  ' . $icheck_id);
            }
        }


        if (count($f) or count($f2)) {
            $notification = new MStaffNotification();
            $notification->content = '<strong>' . count($f) . '</strong> GTIN sai định dạng, <strong>' . count($f2) . '</strong> sai vendor thuộc file ' . $originalFileName . ' chưa được nhập vào hệ thống';
            $notification->type = MStaffNotification::TYPE_IMPORT_PRODUCT_FAILED;
            $notification->data = [
                'gtin_invalid' => $f,
                'vendor_invalid' => $f2,
                'info_e' => [],
            ];
            $notification->unread = true;
            $notification->save();
        }
    }
}

