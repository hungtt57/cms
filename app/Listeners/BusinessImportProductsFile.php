<?php

namespace App\Listeners;

use App\Events\BusinessProductsFileUploaded;
use App\Models\Enterprise\Business;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Excel;
use App\Models\Enterprise\Product;
use App\Models\Enterprise\ProductCategory;
use App\Models\Enterprise\GLN;
use GuzzleHttp\Exception\RequestException;
use App\Models\Enterprise\BusinessNotifications;
use App\Models\Enterprise\MStaffNotification;
//implements ShouldQueue
class BusinessImportProductsFile implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BusinessProductsFileUploaded  $event
     * @return void
     */
    public function handle(BusinessProductsFileUploaded $event)
    {
        $businessId = $event->businessId;
        $business = Business::find($businessId);
        $filepath = $event->filepath;

        $files = Excel::selectSheetsByIndex(0)->load($filepath, function ($reader) {
            $reader->noHeading();
        })->skip(1)->get();

        $eBarcode = array();
        $eB = array();
        $eGLN = array();
        $eImage = array();
        $ePrice = array();
        foreach ($files as $row) {

            if (isset($row[0]) and $row[0]!=null) {
                if(strlen($row[0])!=13){
                    $eBarcode[] = $row[0];
                    continue;
                }
                if(isset($row[8]) and $row[8]==null ){

                    $barcode = $row[0];
                    $gln = $business->gln()->where('status', GLN::STATUS_APPROVED)->get();

                    $gln = $gln->lists('id')->toArray();
                    $productsEx = Product::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();

                    if(!in_array($barcode,$productsEx)){
                        // Error GLN RỖNG,barcode khong thuoc DN
                        $eGLN[] = $barcode;
                        continue;
                    }
                }
                if(isset($row[8]) and $row[8]!=null ){
                    $gln = GLN::where('gln',$row[8])->where('business_id',$businessId)->first();
                    if($gln==null){
                        $eGLN[] = $barcode;
                        continue;
                    }
                    $prefix = $gln->prefix;
                    $checkBarcode = $this->checkBarcode($row[0],$prefix);
                    //check prefix barcode sai vendor
                    if(!$checkBarcode){
                        $eB[] = $row[0];
                        continue;
                    }

                    $checkSKT = $this->checkSKT($row[0],$prefix);
                    //check sai dinh dang
                    if(!$checkSKT){
                        $eBarcode[] = $row[0];
                        continue;
                    }


                }

                if(isset($row[6])) {
                    if (!is_null($row[6])) {
                        if(!is_numeric($row[6])){
                            $ePrice[] =  $row[0];
                            continue;
                        }

                    }
                }

                $product = Product::firstOrCreate(['barcode' => $row[0]]);

                if (!is_null($product)) {
                    $data = [];

                    if(isset($row[1])){
                        if (!is_null($row[1])) {
                            $data['name'] = $row[1];
                            $product->name = $data['name'];
                        }
                    }

                    if(isset($row[6])) {
                        if (!is_null($row[6])) {
                            $data['price'] = trim($row[6]);
                            $product->price = intval($data['price']);
                        }
                    }

                    $data['status'] = Product::STATUS_PENDING_APPROVAL;
                    $product->status = Product::STATUS_PENDING_APPROVAL;

                    $data['attrs'] = $product['attrs'];

                    for ($i = 1; $i < 5; $i++) {
                        $col = $i + 8;

                        if (isset($row[$col]) and !is_null($row[$col])) {
                            $data['attrs'][$i] = nl2br($row[$col]);
                        }
                    }
                    $product['attrs'] = $data['attrs'];

                    for ($i = 2; $i < 6; $i++) {

                        if ((isset($row[$i]) and ($image = @file_get_contents($row[$i])))) {

                            if(intval($this->remotefileSize($row[$i])) > 20480){
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
                                    $data['image'][] = $res->prefix;
                                } catch (RequestException $e) {
                                }
                            }else{
                                $eImage[] = $row[$i];
                            }

                        }
                    }

                    if(isset($data['image'])){
                        $data['image'] = json_encode($data['image']);
                        $product->image = $data['image'];
                    }

                    if (isset($row[8]) and $gln = GLN::where('gln', $row[8])->first()) {
                        $product->gln_id = $gln->id;
                        $product->save();
                    }


                    if(isset($row[7])){
                        $categories = explode('|', $row[7]);
                        foreach ($categories as $cat) {
                            ProductCategory::firstOrCreate(['product_id' => $product->id, 'category_id' => $cat]);
                        }
                    }

                    $product->save();

              }

            }

        }


        if (count($eBarcode) or count($eGLN) or count($eImage) or count($ePrice)) {

            $notification  = new BusinessNotifications();
            $notification->content = '';
            if(count($eBarcode)){
                $notification->content = $notification->content.'<strong>' . count($eBarcode) . '</strong> BARCODE sai định dạng <br>';
            }
            if(count($eGLN)){
                $notification->content = $notification->content.'<strong>' . count($eGLN) . '</strong> GLN sai định dạng .<strong><br>';
            }
            if(count($eImage)){
                $notification->content = $notification->content.'<strong>' . count($eImage) . '</strong>  Ảnh dung lượng nhỏ hơn 20KB .<strong><br>';
            }
            if(count($eB)){
                $notification->content = $notification->content.'<strong>' . count($eB) . '</strong> BARCODE sai vendor <br>';
            }
            if(count($ePrice)){
                $notification->content = $notification->content.'<strong>' . count($ePrice) . '</strong> sai định dạng. <br>';
            }

            $notification->data = [
                'barcode_invalid' => $eBarcode,
                'gln_invalid' => $eGLN,
                'image_invalid' => $eImage,
                'barcode_vendor' => $eB,
                'price_invalid' => $ePrice
            ];
            $notification->business_id = $businessId;
            $notification->data = json_encode($notification->data);
            $notification->unread = 1;

            $notification->save();

        }

        $notification = new MStaffNotification();
        $notification->content = '<strong>' . $business->name . '</strong> đã yêu cầu thêm sản phẩm bằng file';
        $notification->type = MStaffNotification::TYPE_BUSINESS_ADD_PRODUCT_FILE;
        $notification->data = null;
        $notification->unread = true;
        $notification->save();

    }
    function remotefileSize($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_exec($ch);
        $filesize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);
        if ($filesize) return $filesize;
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
    function getCheckCode($barcode){
        $chars = str_split($barcode);
        $le = 0;
        $chan = 0;
        for($i = 0;$i < 12;$i++){
            if(($i+1)%2==1){
                $le = $le + intval($chars[11 - $i]);
            }else{
                $chan = $chan + intval($chars[11 - $i]);
            }
        }
        $le = $le*3;
        $total = $le + $chan;


        if($total%10 == 0){
            return 0;
        }else{
            $a = (intval($total/10)+1)*10;
            $checkCode = $a - $total;
            return $checkCode;
        }

    }
}
