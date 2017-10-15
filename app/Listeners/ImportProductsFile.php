<?php

namespace App\Listeners;

use App\Events\ProductsFileUploaded;
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
use App\Models\Icheck\Product\ProductInfo;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use App\Jobs\TestJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Str;
// implements ShouldQueue
class ImportProductsFile implements ShouldQueue
{
    use DispatchesJobs;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  ProductsFileUploaded $event
     * @return void
     */
    public function handle(ProductsFileUploaded $event)
    {
        set_time_limit(300);
        ini_set('memory_limit', '2048M');


        $new = $event->new;
        $filepath = $event->filepath;
        $originalFileName = $event->originalFileName;
        $prefix = $event->prefix;
        $vendor = $event->vendor;
//       $new 1 is add
//        $new 0 is update


        if ($new) {

            $data = Excel::selectSheetsByIndex(0)->load($filepath, function ($reader) {
                $reader->noHeading();
            })->skip(1)->get();

            $f = array();
            $f2 = array();
            $f3 = array();

            foreach ($data as $row) {

                $gln = null;
                if (!$row[0]) {
                    continue;
                }
                if($vendor){
                    if (!validate_barcode(trim($row[0]))) {

                        $f[] = $row[0];
                        continue;
                    }
                }

                if (trim($row[10]) and !($gln = Vendor::where('gln_code', trim($row[10]))->first())) {

                    $f2[] = $row[0];
                    continue;
                }

                // add notification
                if($prefix){
                    $count  = Product::where('gtin_code',trim($row[0]))->first();
                    if(empty($count)){

                        if($row[10]==null){

                            $f2[] = $row[0];
                            continue;
                        }
                        $prefix = $gln->prefix;
                        if(empty($prefix)){

                            $f2[] = $row[0];
                            continue;
                        }
                        $checkBarcode = $this->checkBarcode($row[0],$prefix);
                        if(!$checkBarcode){

                            $f2[] = $row[0];
                            continue;
                        }

                    }else{

                        if($gln){
                            $prefix = $gln->prefix;
                            if(empty($prefix)){

                                $f2[] = $row[0];
                                continue;
                            }
                            $checkBarcode = $this->checkBarcode($row[0],$prefix);
                            if(!$checkBarcode){

                                $f2[] = $row[0];
                                continue;
                            }
                        }


                    }
                }


                //end add notification
                $product = Product::where('gtin_code', trim($row[0]))->first();
                if(is_null($product)){
                    $product = new Product();
                    $product->internal_code = 'ip_'.microtime(true) * 10000;
                    $product->gtin_code = trim($row[0]);
                    if (isset($gln)) {
                        $product->vendor_id = $gln->id;
                    }
                    $product->save();
                }

                if (!is_null($product)) {
                    $add = false;
                    $product_data = [];

                    if ($product->product_name) {
                        $f3[] = $row[0];

                        continue;
                    }

                    if (!$product->internal_code) {
                        $add = true;
                        if (!isset($gln)) {

                            $f2[] = $row[0];

                            $product->delete();

                            continue;
                        }
                        $product_data['internal_code'] = 'ip_' . microtime(true) * 10000;
                        $product->internal_code = $product_data['internal_code'];
                    }

                    if (isset($row[1])) {
                        if (!is_null($row[1])) {
                            $product_data['product_name'] = $row[1];
                            $product->product_name = $product_data['product_name'];
                        }
                    }
                    if (isset($row[8])) {
                        if (!is_null($row[8])) {
                            $product_data['price_default'] = $row[8];
                            $product->price_default = $product_data['price_default'];
                        }
                    }
                    if (isset($row[2])) {
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
                                $product_data['image_default'] = $res->prefix;
                                $product->image_default = $product_data['image_default'];
                            } catch (RequestException $e) {
                            }
                        }
                    }
//                    $product->update($product_data);

                    $product_image = array();
                    for ($i = 3; $i < 6; $i++) {
                        if (isset($row[$i])) {
                            if ((!is_null($row[$i]) and ($image = @file_get_contents($row[$i])))) {
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


                    if (isset($row[9])) {
                        if (!is_null($row[9]) and $row[9] !== '') {
                            $categories = explode('|', $row[9]);
                            $product->categories()->sync($categories);
                            $product->in_categories = implode(',',$categories);
                        }
                    }


                    if (isset($gln) && (empty($product->vendor_id))) {
                        $product->vendor_id = $gln->id;
                    }


                    for ($i = 1; $i < 5; $i++) {
                        $col = $i + 10;

                        if (isset($row[$col]) && !empty(trim($row[$col]))) {
                            $count = ProductInfo::where('attribute_id', $i)->where('product_id', $product->id)->count();

                            if ($count > 0) {
                                $product->attributes()->updateExistingPivot($i, ['content' => nl2br($row[$col]), 'content_text' => strip_tags($row[$col])  ,'short_content' => Str::words(strip_tags($row[$col]),300,'')]);

                            } else {
                                $info = new ProductInfo;
                                $info->product_id = $product->id;
                                $info->attribute_id = $i;
                                $info->content = nl2br($row[$col]);
                                $info->content_text = strip_tags($row[$col]);
                                $info->short_content =  Str::words(strip_tags($row[$col]),300,'');
                                $info->save();
                            }

                        }
                    }


                    $pproduct = PProduct::where('gtin_code', $product->gtin_code)->first();

                    if ($pproduct == null) {
                        $pproduct = new PProduct();
                        $pproduct->gtin_code = $product->gtin_code;
                        $pproduct->save();
                    }
                    // Push anh len product mongo
                    if (isset($product_image) && count($product_image) > 0) {
//                        $pproduct->unset('attachments');
                        foreach ($product_image as $image) {

                            $pproduct->push('attachments',[
                                    'type' => 'image',
                                    'link' => $image,
                                ]);

                        }

                    }
                    if(isset($row[6]) && $row[6] != null){
                        $pproduct->push('attachments',[
                            'type' => 'video',
                            'link' => $row[6],
                        ]);
                    }
                    if(isset($row[7]) && $row[7] != null){
                        $pproduct->push('attachments',[
                            'type' => 'video',
                            'link' => $row[7],
                        ]);
                    }
                    //keywords
//                    if(isset($row[18]) ){
//                        $product->keywords = $row[18];
//                    }
//                    if(isset($row[19])){
//                        $product->score = $row[19];
//                    }
                    if(isset($row[18]) && isset($row[19]) && $row[18] != null && $row[19]!=null ){
                        $product->date_validity = $row[18];
                        $product->expiration_date = $row[19];
                    }
                    $product->save();

                    //Call api dong bo redis
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


                    ContributeProduct::where('gtin', $product->gtin_code)->whereIn('status', [ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->delete();

                    if ($add) {
                        \App\Models\Enterprise\MLog::create([
                            'email' => $event->email,
                            'action' => 'Thêm sản phẩm ' . $product->product_name . '(' . $product->gtin_code . ') bằng file',
                        ]);
                    } else {
                        \App\Models\Enterprise\MLog::create([
                            'email' => $event->email,
                            'action' => 'Sửa sản phẩm ' . $product->product_name . '(' . $product->gtin_code . ') bằng file',
                        ]);
                    }
                    if(isset($row[15]) && isset($row[16]) && isset($row[17])){

                        if ($row[15] != null && $row[16]!=null && $row[17]!=null) {
                            $start_date = $row[16];
                            $end_date = $row[17];
                            $gtins =  explode('|', $row[15]);

                            $name = 'prod:' . $product->gtin_code;
                            $hook = Hook::firstOrCreate(['name' => $name]);
                            $hook->iql = null;
                            $hook->type=0;
                            $hook->save();

                            HookProduct::where('hook_id',$hook->id)->delete();
                            foreach ($gtins as $gtin){
                                $product = Product::where('gtin_code',$gtin)->first();
                                if($product){
                                    $hook_product = HookProduct::firstOrCreate(['hook_id' => $hook->id,'product_id'=> $gtin]);
                                    $hook_product->start_date = $start_date;
                                    $hook_product->end_date = $end_date;
                                    $hook_product->save();
                                }

                            }
                        }
                    }

                }

            }

            if (count($f) or count($f2) or count($f3)) {

                $notification = new MStaffNotification();
                $notification->content = '<strong>' . count($f) . '</strong> GTIN sai định dạng, <strong>' . count($f2) . '</strong>, <strong>' . count($f3) . '</strong> đã có thông tin thuộc file ' . $originalFileName . ' chưa được nhập vào hệ thống';
                $notification->type = MStaffNotification::TYPE_IMPORT_PRODUCT_FAILED;
                $notification->data = [
                    'gtin_invalid' => $f,
                    'vendor_invalid' => $f2,
                    'info_e' => $f3,
                ];
                $notification->unread = true;
                $notification->save();

            }
        } else {
            $data = Excel::selectSheetsByIndex(0)->load($filepath, function ($reader) {
                $reader->noHeading();
            })->skip(1)->get();

            $f = [];
            $f2 = [];



            foreach ($data as $row) {
                $gln = null;
                if (!$row[0]) {

                    continue;
                }
                if($vendor){
                    if (!validate_barcode(trim($row[0]))) {

                        $f[] = $row[0];
                        continue;
                    }

                }

                if (trim($row[10]) and !($gln = Vendor::where('gln_code', trim($row[10]))->first())) {

                    $f2[] = $row[0];
                    continue;
                }


                // add notification
                if($prefix){
                    $count  = Product::where('gtin_code',trim($row[0]))->first();
                    if(empty($count)){
                        if($row[10]==null){
                            $f2[] = $row[0];
                            continue;
                        }
                        $prefix = $gln->prefix;
                        if(empty($prefix)){
                            $f2[] = $row[0];
                            continue;
                        }
                        $checkBarcode = $this->checkBarcode($row[0],$prefix);
                        if(!$checkBarcode){
                            $f2[] = $row[0];
                            continue;
                        }
                    }else{

                        if($gln){
                            $prefix = $gln->prefix;
                            if(empty($prefix)){

                                $f2[] = $row[0];
                                continue;
                            }
                            $checkBarcode = $this->checkBarcode($row[0],$prefix);
                            if(!$checkBarcode){

                                $f2[] = $row[0];
                                continue;
                            }
                        }


                    }
                }


                //end add notification


//                $product = Product::firstOrCreate(['gtin_code' => trim($row[0])]);
                $product = Product::where('gtin_code', trim($row[0]))->first();
                if(is_null($product)){
                    $product = new Product();
                    $product->internal_code = 'ip_'.microtime(true) * 10000;
                    $product->gtin_code = trim($row[0]);
                    $product->save();
                }

                if (!is_null($product)) {
                    $add = false;
                    $product_data = [];

                    if (!$product->internal_code) {
                        $add = true;
                        if (!isset($gln)) {
                            $f2[] = $row[0];
                            $product->delete();

                            continue;
                        }

                        $product_data['internal_code'] = 'ip_' . microtime(true) * 10000;
                        $product->internal_code = $product_data['internal_code'];
                    }
                    if (isset($row[1])) {
                        if (!is_null($row[1])) {
                            $product_data['product_name'] = $row[1];
                            $product->product_name = $product_data['product_name'];
                        }
                    }
                    if (isset($row[8])) {
                        if (!is_null($row[8])) {
                            $product_data['price_default'] = $row[8];
                            $product->price_default = $product_data['price_default'];
                        }
                    }
                    if (isset($gln) && (empty($product->vendor_id))) {
                        $product->vendor_id = $gln->id;
                        $product->save();
                    }

                    if (isset($row[2])) {
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
                                $product_data['image_default'] = $res->prefix;
                                $product->image_default = $product_data['image_default'];
                            } catch (RequestException $e) {

                            }
                        }
                    }
                    $product_image = array();
                    for ($i = 3; $i < 6; $i++) {
                        if (isset($row[$i])) {
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
//                    $product->update($product_data);

                    if (isset($row[9])) {
                        if (!is_null($row[9]) and $row[9] !== '') {
                            $categories = explode('|', $row[9]);
                            $product->categories()->sync($categories);
                            $product->in_categories = implode(',',$categories);

                        }
                    }



                    for ($i = 1; $i < 5; $i++) {
                        $col = $i + 10;

                        if (isset($row[$col]) && !empty(trim($row[$col]))) {
                            $count = ProductInfo::where('attribute_id', $i)->where('product_id', $product->id)->count();

                            if ($count > 0) {
                                $product->attributes()->updateExistingPivot($i, ['content' => nl2br($row[$col]), 'content_text' => strip_tags($row[$col])  ,'short_content' => Str::words(strip_tags($row[$col]),300,'')]);
                            } else {
                                $info = new ProductInfo;
                                $info->product_id = $product->id;
                                $info->attribute_id = $i;
                                $info->content = nl2br($row[$col]);
                                $info->content_text = strip_tags($row[$col]);
                                $info->short_content =  Str::words(strip_tags($row[$col]),300,'');
                                $info->save();
                            }
                            $product->updatedAt = \Carbon\Carbon::now();
                        }
                    }

                    //Call api dong bo redis
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

                    $pproduct = PProduct::where('gtin_code', $product->gtin_code)->first();

                    if ($pproduct == null) {
                        $pproduct = new PProduct();
                        $pproduct->gtin_code = $product->gtin_code;
                        $pproduct->save();
                    }

                    $images = $pproduct->attachments;
                    $pproduct->unset('attachments');

                    if($images!=null){
                        foreach ($images as $key => $img) {
                            if (isset($images[$key]['type'])) {
                                if ($images[$key]['type'] == 'image') {
                                    unset($images[$key]);
                                }elseif($images[$key]['type'] == 'video'){
                                    unset($images[$key]);
                                }
                                else{
                                    $pproduct->push('attachments',$images[$key]);
                                }
                            }

                        }
                    }
                    // Push anh len product mongo
                    if (isset($product_image) && count($product_image) > 0) {

                        foreach ($product_image as $image) {

                            $pproduct->push('attachments',[
                                'type' => 'image',
                                'link' => $image,
                            ]);


                        }

                    }
                    if(isset($row[6]) && $row[6] != null){
                        $pproduct->push('attachments',[
                            'type' => 'video',
                            'link' => $row[6],
                        ]);
                    }
                    if(isset($row[7]) && $row[7] != null){
                        $pproduct->push('attachments',[
                            'type' => 'video',
                            'link' => $row[7],
                        ]);
                    }
                    //keywords
//                    if(isset($row[18]) ){
//                        $product->keywords = $row[18];
//                    }
//                    if(isset($row[19])){
//                        $product->score = $row[19];
//                    }
                    if(isset($row[18]) && isset($row[19]) && $row[18] != null && $row[19]!=null ){
                        $product->date_validity = $row[18];
                        $product->expiration_date = $row[19];
                    }
                    $product->save();

                    //Call api dong bo redis
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
                    ContributeProduct::where('gtin', $product->gtin_code)->whereIn('status', [ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->delete();

                    if ($add) {
                        \App\Models\Enterprise\MLog::create([
                            'email' => $event->email,
                            'action' => 'Thêm sản phẩm ' . $product->product_name . '(' . $product->gtin_code . ') bằng file',
                        ]);
                    } else {
                        \App\Models\Enterprise\MLog::create([
                            'email' => $event->email,
                            'action' => 'Sửa sản phẩm ' . $product->product_name . '(' . $product->gtin_code . ') bằng file',
                        ]);
                    }
                    if(isset($row[15]) && isset($row[16]) && isset($row[17])){

                        if ($row[15] != null && $row[16]!=null && $row[17]!=null) {
                            $start_date = $row[16];
                            $end_date = $row[17];
                            $gtins =  explode('|', $row[15]);

                            $name = 'prod:' . $product->gtin_code;
                            $hook = Hook::firstOrCreate(['name' => $name]);
                            $hook->iql = null;
                            $hook->type=0;
                            $hook->save();

                            HookProduct::where('hook_id',$hook->id)->delete();
                            foreach ($gtins as $gtin){
                                $product = Product::where('gtin_code',$gtin)->first();
                                if($product){
                                    $hook_product = HookProduct::firstOrCreate(['hook_id' => $hook->id,'product_id'=> $gtin]);
                                    $hook_product->start_date = $start_date;
                                    $hook_product->end_date = $end_date;
                                    $hook_product->save();
                                }

                            }
                        }
                    }

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

    function checkBarcode($barcode,$prefix){
        if(strpos($barcode,$prefix) === false){
            return false;
        }
        return true;

    }
    function checkSKT($barcode,$prefix){
        $checkCode = substr($barcode,-1);
        $barcode = substr($barcode,0,12);
        if($checkCode==$this->getCheckCode($barcode)){
            return true;
        }
        return false;
    }
}
