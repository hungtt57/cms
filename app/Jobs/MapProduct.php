<?php namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Enterprise\MStaffNotification;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Icheck\Product\Product;
use App\Models\Mongo\Product\PProduct;
use App\Models\Icheck\Product\ProductInfo;
use App\Models\Craw\Product as ProductCraw;
use Illuminate\Support\Str;
//extends Job implements ShouldQueue
class MapProduct
{
    use InteractsWithQueue, SerializesModels;
    private  $data;
    private $ids;
    private $mapProductId;
    public $createBy;
    public $jobName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ids,$mapProductId,$data,$email)
    {
        $this->data = $data;
        $this->ids = $ids;
        $this->mapProductId = $mapProductId;
        $this->createBy = $email;
        $this->jobName = "Map sản phẩm hệ thống";
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ids = $this->ids;
        $mapProductId = $this->mapProductId;
        $data = $this->data;
        $imageErr = [];
        $barcodeErr = [];
        foreach ($ids as $value){
            $id = intval($value);
            $product = Product::find($id);
            if(!$product){
                continue;
            }
            if(!isset($mapProductId[$id])){
                continue;
            }
            if(!isset($data[$id])){
                continue;
            }
            $mapId = $mapProductId[$id];

            foreach($data[$id] as $p){
                if($p['id'] == $mapId){
                    $mapProduct = $p;
                }
            }
            if(empty($mapProduct)){
                continue;
            }

            if($mapProduct['text']){
                $product->product_name = $mapProduct['text'];
            }
            if($mapProduct['price']){
                $product->price_default = $mapProduct['price'];
            }
            if($mapProduct['description']){
                $content = $mapProduct['description'];
                if (trim($content)) {
                    $attrs[1] = ['content' => $content
                        , 'content_text' => strip_tags($content)
                        , 'short_content' => Str::words(strip_tags($content), 300, '')
                    ];
                    $count = ProductInfo::where('attribute_id', 1)->where('product_id', $product->id)->count();
                    if ($count > 0) {
                        $product->attributes()->updateExistingPivot(1, $attrs[1]);
                    } else {
                        $info = new ProductInfo;
                        $info->product_id = $product->id;
                        $info->attribute_id = 1;
                        $info->content = $content;
                        $info->content_text = strip_tags($content);
                        $info->short_content = Str::words(strip_tags($content), 300, '');
                        $info->save();
                    }
                } else {
                    $product->attributes()->detach(1);
                }
            }

            if($mapProduct['images']){
                $dataImage = [];
                $images = $mapProduct['images'];
                foreach ($images as $img){
                    if(strpos($img,'http://ucontent.icheck.vn/') !== false){
                         preg_match("/(?<=http:\/\/ucontent.icheck.vn\/)(.*)(?=_original.jpg)/",$img,$name);
                        if(isset($name[0])){
                            $dataImage[] = $name[0];
                            continue;
                        }
                    }
                    if ($image = @file_get_contents($img)) {
                        if(intval($this->remotefileSize($img)) > 20480){

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
                                $dataImage[] = $res->prefix;
                            } catch (\Exception $e) {

                            }
                        }
                    }
                }
                // push ảnh
                if($dataImage){
                    $data = $dataImage;
                    if($product->image_default == ''){
                        $product->image_default = $data[0];
                    }
                    $m = PProduct::where('gtin_code', $product->gtin_code)->first();

                    if ($m) {
                        foreach ($data as $image) {
                            if ($image != $product->image_default) {
                                $m->push('attachments', [
                                    'type' => 'image',
                                    'link' => $image,
                                ]);
                            }

                        }
                    } else {
                        $pproduct = new PProduct();
                        $pproduct->gtin_code = $product->gtin_code;
                        $pproduct->internal_code = $product->internal_code;
                        $pproduct->save();

                        foreach ($data as $image) {
                            if ($image != $product->image_default) {
                                $pproduct->push('attachments', [
                                    'type' => 'image',
                                    'link' => $image,
                                ]);
                            }

                        }
                    }
                }else{
                    $barcodeErr[] = $product->gtin_code;
                }
            }

            $product->mapped = 1;
            $product->save();
        }
        if (count($barcodeErr) ) {
            $notification = new MStaffNotification();
            $notification->content = '<strong>Mã sản phẩm được map vào hệ thống nhưng map không có ảnh </strong>';
            $notification->type = MStaffNotification::TYPE_IMPORT_PRODUCT_FAILED;
            $notification->data = [
                'gtin_invalid' => $barcodeErr,
                'info_e' => [],
            ];
            $notification->unread = true;
            $notification->save();
        }
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
