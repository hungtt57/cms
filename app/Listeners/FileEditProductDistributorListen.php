<?php

namespace App\Listeners;

use App\Events\FileEditProductDistributor;
use App\Models\Enterprise\Business;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Excel;
use App\Models\Icheck\Product\Product;
use GuzzleHttp\Exception\RequestException;
use App\Models\Enterprise\Product as ProductDN;
use App\Models\Enterprise\ProductDistributorTemp;
use App\Models\Enterprise\ProductDistributor;
use DB;
use App\Models\Enterprise\BusinessNotifications;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\MStaffNotification;
//implements ShouldQueue
class FileEditProductDistributorListen implements ShouldQueue
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

    public function handle(FileEditProductDistributor $event)
    {
        $businessId = $event->businessId;
        $filepath = $event->filepath;

        $data = Excel::selectSheetsByIndex(0)->load($filepath, function ($reader) {
            $reader->noHeading();
        })->skip(1)->get();

        //var_dump($data);
        $eBarcodePP = array();
        $eImage = array();
        $eEditPP = array();
        $eSX = array();
        $ePrice = array();
        $business = Business::find($businessId);
        $gln = $business->gln()->where('status', GLN::STATUS_APPROVED)->get();
        $gln = $gln->lists('id')->toArray();
        $barcodes = ProductDN::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();

        foreach ($data as $row) {

            if (isset($row[0]) and $row[0]!=null) {
                $product = Product::where('gtin_code',$row[0])->first();
                $pD=null;
                $check = 0;
                if($product){
                    if(in_array($row[0],$barcodes)){
                        $eSX[] = $row[0];
                        continue;
                    }
                    $is_first = 0;
                    $count = ProductDistributor::where('product_id',$product->id)->where('is_first',1)->count();

                    if($count == 0){
                        $is_first = 1;
                    }
                    $pD = ProductDistributor::firstOrCreate(['business_id'=>$businessId,'product_id' => $product->id]);
                    if($pD->is_first!=1){
                        $pD->is_first = $is_first;
                    }
                    $pD->status=ProductDistributor::STATUS_ACTIVATED;
                    $pD->save();
                    $check = $pD->is_first;

                } else{
                    $eBarcodePP[] = $row[0];
                    continue;
                }

                if($check==0){
                    $eEditPP[] = $row[0];
                    continue;
                }
                if ($pD!=null and $check) {
                    $data = [];


                    if(isset($row[6])) {
                        if (!is_null($row[6])) {
                            $data['price'] = $row[6];
                            if(!is_numeric($data['price'])){
                                $ePrice[] =  $row[0];
                                continue;
                            }

                        }
                    }
                    $productDistributor = ProductDistributorTemp::firstOrCreate(['gtin_code' => $row[0],'business_id' => $businessId]);

                    if(isset($row[1])){
                        if (!is_null($row[1])) {
                            $data['name'] = $row[1];
                            $productDistributor->product_name = $data['name'];
                        }
                    }

                    if(isset($row[6])) {
                        if (!is_null($row[6])) {
                            $data['price'] = $row[6];
                            $productDistributor->price = $data['price'];
                        }
                    }


                    $productDistributor->status =  ProductDistributorTemp::STATUS_PENDING_APPROVAL;

                    $data['attrs'] = null;
                    for ($i = 1; $i < 3; $i++) {
                        $col = $i + 7;

                        if (isset($row[$col]) and !is_null($row[$col])) {
                            $data['attrs'][$i] = nl2br($row[$col]);
                        }

                    }
                    if (isset($row[10]) and !is_null($row[10])) {
                        $data['attrs'][4] = nl2br($row[10]);
                    }

                    $productDistributor->attrs= json_encode($data['attrs']);


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

                                $eImage[] =$row[$i];
                            }

                        }
                    }

                    if(isset($data['image'])){
                        $data['image'] = json_encode($data['image']);
                        $productDistributor->image = $data['image'];
                    }

                    if(isset($row[7])){
                        $categories = explode('|', $row[7]);
                        if(is_array($categories)){
                            $productDistributor->categories = json_encode($categories);
                        }

                    }

                    $productDistributor->save();

                }
            }
        }
//
        if (count($eBarcodePP) or count($eEditPP) or count($eImage) or count($eSX)   or count($ePrice)) {

            $notification  = new BusinessNotifications();
            $notification->content = '';

            if(count($eBarcodePP)){
                $notification->content = $notification->content.'<strong>' . count($eBarcodePP) . '</strong> BARCODE không tồn tại trong hệ thống<br>';
            }
            if(count($eEditPP)){
                $notification->content = $notification->content.'<strong>' . count($eEditPP) . '</strong>Không có quyền sửa barcode.<strong><br>';
            }
            if(count($eImage)){
                $notification->content = $notification->content.'<strong>' . count($eImage) . '</strong>  Ảnh dung lượng nhỏ hơn 20KB .<strong><br>';
            }
            if(count($eSX)){
                $notification->content = $notification->content.'<strong>' . count($eSX) . '</strong>  BARCODE là mã sản xuất .<strong><br>';
            }
            if(count($ePrice)){
                $notification->content = $notification->content.'<strong>' . count($ePrice) . '</strong> sai định dạng sản phẩm phân phối <br>';
            }
            $notification->data = [
                'barcodePP_invalid' => $eBarcodePP,
                'image_invalid' => $eImage,
                'editPP_invalid' => $eEditPP,
                'Sx_invalid' => $eSX,
                'price_invalid' => $ePrice
            ];
            $notification->business_id = $businessId;
            $notification->data = json_encode($notification->data);
            $notification->unread = 1;

            $notification->save();

        }
        $notification = new MStaffNotification();
        $notification->content = '<strong>' . $business->name . '</strong> đã yêu cầu sửa sản phẩm phân phối bằng file';
        $notification->type = MStaffNotification::TYPE_BUSINESS_EDIT_PRODUCTPP_FILE;
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
}
