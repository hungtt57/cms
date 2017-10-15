<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Collaborator\ContributeProduct;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Icheck\Product\Product;
use App\Models\Mongo\Product\PProduct;
use App\Models\Icheck\Product\Contribute;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\Icheck\Product\AttrValue;
//extends Job implements ShouldQueue
class ApproveListContributeUserJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $ids;
    protected $email;
    public $createBy;
    public $jobName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ids,$email)
    {
        $this->ids = $ids;
        Contribute::whereIn('id',$ids)->update([
            'status' => Contribute::STATUS_IN_PROGRESS,
        ]);
        $this->email = $email;
        $this->createBy = $email;
        $this->jobName = 'Approve sản phẩm đóng góp của user';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ids = $this->ids;
        $email = $this->email;
        foreach ($ids as $id) {
            $contribute = Contribute::where('id', $id)->where('status', Contribute::STATUS_IN_PROGRESS)->first();
            if ($contribute) {
                $product = Product::where('gtin_code', $contribute->gtin_code)->first();
                if (empty($product)) {
                    $product = new Product();
                }

                if ($contribute->product_name != '') {
                    $product->product_name = $contribute->product_name;
                }
                if ($contribute->price > 0) {
                    $product->price_default = $contribute->price;
                }

                $product->gtin_code = $contribute->gtin_code;

                $images = array();
                $data_images = $contribute->attachments;
                if($data_images){
                    $data_images = json_decode($data_images);
                    foreach ($data_images as $img) {
                        if ($img->type == 'image') {
                            $images[] = $img->link;
                        }
                    }

                }

                if (count($images) > 0) {

                    $pproduct = PProduct::where('gtin_code', $contribute->gtin_code)->first();

                    if (empty($pproduct)) {

                        $pproduct = new PProduct();
                        $pproduct->gtin_code = $product->gtin_code;
                        $pproduct->internal_code = $product->internal_code;
                        $pproduct->save();

                        foreach ($images as $image) {
                            if ($image != $product->image_default) {
                                $pproduct->push('attachments', [
                                    'type' => 'image',
                                    'link' => $image,
                                    'owner_id' => $contribute->icheck_id
                                ]);
                            }

                        }

                    } else {

                        $pimages = $pproduct->attachments;

                        if ($product->image_default == '') {
                            $product->image_default = $images[0];
                        }

                        foreach ($images as $image) {
                            if ($image != $product->image_default) {
                                $pproduct->push('attachments', [
                                    'type' => 'image',
                                    'link' => $image,
                                    'owner_id' => $contribute->icheck_id
                                ]);
                            }

                        }

                        $pproduct->save();
                    }

                } else {
                    $pproduct = new PProduct();
                    $pproduct->gtin_code = $product->gtin_code;
                    $pproduct->internal_code = $product->internal_code;
                    $pproduct->save();
                }
                if($contribute->categories){
                    $categories = json_decode($contribute->categories,true);
                    if($categories and is_array($categories)){
                        $product->categories()->sync($categories);
                        $product->in_categories = implode(',',$categories);
                    }
                }
                if(json_decode($contribute->properties,true)){
                    $count_features = 0;
                    $features = [];
                    AttrValue::where('product_id',$product->id)->delete();
                    $properties = json_decode($contribute->properties,true);
                    foreach ($properties as $key =>  $property){
                        if(count($property) > 3){
                            if($count_features < 10){
                                if(trim($property[0])){
                                    $features[] = trim( $property[0]);
                                    $count_features++;
                                }
                            }
                            if($count_features < 10){
                                if(trim( $property[1])){
                                    $features[] = trim( $property[1]);
                                    $count_features++;
                                }
                            }
                            if($count_features < 10){
                                if(trim( $property[2])){
                                    $features[] = trim( $property[2]);
                                    $count_features++;
                                }
                            }
                        }else{
                            foreach ($property as $v){
                                if($count_features < 10){
                                    if(trim($v)){
                                        $features[] = trim($v);
                                        $count_features++;
                                    }

                                }
                            }
                        }
                        $v = implode(',',$property);
                        $attr_value = [
                            'product_id' => $product->id,
                            'attribute_id' => $key,
                        ];
                        if($v){
                            $a = AttrValue::firstOrCreate($attr_value);
                            $a->content = $v;
                            $a->save();
                        }

                    }
                    if($features){
                        $f = implode(',',$features);
                        $product->features = $f;
                    }
                }
                $contribute->status = Contribute::STATUS_APPROVED;
                $contribute->save();

                $product->save();
                $contribute_product = Contribute::where('gtin_code', $contribute->gtin_code)->where('id', '<>', $id)->update(['status' => Contribute::STATUS_DISAPPROVED]);


                //delete gtin_code in contribute by ctv
                ContributeProduct::whereIn('status', [ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->where('gtin', $product->gtin_code)->delete();


                \App\Models\Enterprise\MLog::create([
                    'email' => $email,
                    'action' => 'Approve sản phẩm Contribute ' . $product->product_name . '(' . $product->gtin_code . ')',
                ]);

                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request('PUT', env('DOMAIN_API') . 'products/' . $product->id, [
                        'auth' => [env('USER_API'), env('PASS_API')],
                    ]);
                    $res = json_decode((string)$res->getBody());

                    if ($res->status != 200){

                    }

                } catch (RequestException $e) {
//                    return $e->getResponse()->getBody();
                }
            }
        }

    }
}
