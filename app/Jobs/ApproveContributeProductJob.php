<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Collaborator\ContributeProduct;
//use App\Models\Social\Product as SicialProduct;

//use App\Models\Social\MProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Icheck\Product\Product as SicialProduct;
use App\Models\Mongo\Product\PProduct;
use App\Models\Icheck\Product\Contribute;
use App\Models\Icheck\Product\ProductInfo;
use Illuminate\Support\Str;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\AttrValue;
//extends Job implements ShouldQueue
use App\Models\Enterprise\CollaboratorHistoryMoney;
//extends Job implements ShouldQueue
class ApproveContributeProductJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $product;
    protected $note;
    protected $amount;
    public $createBy;
    public $jobName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product, $note, $amount, $staff,$email)
    {

        $product->update([
            'note' => $note,
            'amount' => $amount,
            'status' => ContributeProduct::STATUS_IN_PROGRESS,
            'approvedAt' => Carbon::now(),
            'approvedBy' => $staff,
        ]);

        $this->product = $product;
        $this->amount = $amount;
        $this->createBy = $email;
        $this->jobName = 'Approve sản phẩm ctv';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = $this->product;

        $sp = SicialProduct::where('gtin_code', $product->gtin)->first();
        if(empty($sp)){
            $sp = new SicialProduct();
            $sp->gtin_code = $product->gtin;
            $sp->internal_code = 'ip_'.microtime(true) * 10000;
            $sp->save();
        }
        if ($sp) {

            $sp->product_name = $product->name;

            if ($product->price) {
                $sp->price_default = $product->price;
            }

            $mp = PProduct::where('gtin_code',$product->gtin)->first();


            if (empty($mp)) {
                $mp = new PProduct();
                $mp->gtin_code = $product->gtin;
                $mp->save();
            }

            $images = [];

            if (count($product->images)) {
                $d = false;

                foreach ($product->images as $img) {
                    if ($img['default'] == true) {
                        if (isset($img['path'])) {
                            $sp->image_default = $img['path'];

                            $d = true;
                        }
                    } else {
                        if (isset($img['path'])) {
                            $images[] = $img['path'];
                        }
                    }
                }

                if (!$d) {
                    if (count($images)) {
                        $sp->image_default = $images[0];

                        array_shift($images);
                    }
                }
            }

            if ($mp) {

                $attachments = $mp->attachments;
                $mp->unset('attachments');
                if($attachments!=null){
                    foreach ($attachments as $key => $img) {
                        if (isset($attachments[$key]['type'])) {
                            if ($attachments[$key]['type'] == 'image') {
                                unset($attachments[$key]);
                            }else{
                                $mp->push('attachments',$attachments[$key]);
                            }
                        }

                    }
                }

                foreach ($images as $image) {
                    $mp->push('attachments',[
                            'type' => 'image',
                            'link' => $image,
                        ]);

                }


                $mp->save();
            }
            if (isset($product->categories) and is_array($product->categories)) {
                $cids = [];

                foreach ($product->categories as $c) {
                    $cids[] = $c['id'];
                }

                $sp->categories()->sync($cids);
                $sp->in_categories = implode(',',$cids);
                $sp->save();
            };

            if ($mp) {
                $mp->save();
            }
            //add thuoc tinh
            if(isset($product->properties) and is_array($product->properties)){

                $features = [];
                $count_features = 0;
                foreach ($product->properties as $key => $value) {
                    if($value){
                        if(count($value) > 3){
                            if($count_features < 10){
                                if(trim( $value[0])){
                                    $features[] = trim( $value[0]);
                                    $count_features++;
                                }

                            }
                            if($count_features < 10){
                                if(trim( $value[1])){
                                    $features[] = trim( $value[1]);
                                    $count_features++;
                                }
                            }
                            if($count_features < 10){
                                if(trim( $value[2])){
                                    $features[] = trim( $value[2]);
                                    $count_features++;
                                }
                            }
                        }else{
                            foreach ($value as $v){
                                if($count_features < 10){
                                    if(trim($v)){
                                        $features[] = trim($v);
                                        $count_features++;
                                    }

                                }
                            }
                        }
                        $value = implode(',',$value);
                        $data = [
                            'product_id' => $sp->id,
                            'attribute_id' => $key,
                        ];
                        if($value){
                            $a = AttrValue::firstOrCreate($data);
                            $a->content = $value;
                            $a->save();
                        }

                    }
                }
                if($features){
                    $f = implode(',',$features);
                    $sp->features = $f;

                }

            }
            if(isset($product->attributes)){
                foreach ($product->attributes as $attr) {
                    if ($attr['content']) {
                        $count = ProductInfo::where('attribute_id', $attr['id'])->where('product_id', $sp->id)->count();
//                        $attr['content'] = str_replace(array("\n", "\r"), '', $attr['content']);
                        if ($count > 0) {
                            $sp->attributes()->updateExistingPivot($attr['id'], ['content' => nl2br( $attr['content']), 'content_text' => strip_tags( $attr['content'])  ,'short_content' => Str::words(strip_tags( $attr['content']),300,'')]);
                        } else {
                            $info = new ProductInfo;
                            $info->product_id = $sp->id;
                            $info->attribute_id =$attr['id'];
                            $info->content =  nl2br( $attr['content']);
                            $info->content_text =strip_tags( $attr['content']);
                            $info->short_content =  Str::words(strip_tags($attr['content']),300,'');
                            $info->save();
                        }

                    }

                }
            }




             $product->update([
                'status' => ContributeProduct::STATUS_APPROVED,
            ]);

            $sp->save();
            if($product->gln_code){
                if ($vendor = Vendor::where('gln_code', $product->gln_code)->first()) {
                    $sp->vendor_id = $vendor->id;
                    $sp->save();
                }
            }

            //Call api dong bo redis
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('PUT', env('DOMAIN_API') . 'products/' . $sp->id, [
                    'auth' => [env('USER_API'), env('PASS_API')],
                ]);
                $res = json_decode((string)$res->getBody());

                if ($res->status != 200) Log::info('Loi cap nhat redis product:  ' . $sp->id);
            } catch (RequestException $e) {
                Log::info('Loi cap nhat redis product:  ' . $sp->id);
            }

            Contribute::where('gtin_code',$product->gtin_code)->update(['status'=>Contribute::STATUS_DISAPPROVED]);

            $product->contributor()->increment('balance', $this->amount);

            // them history
            $history_data  = [
              'collaborator_id' =>  $product->contributor->id,
                'money' => $this->amount,
                'group_id' =>  $product->contributor->group,
                'gtin' => $product->gtin,
                'date' => $product->contributedAt
            ];
            $history =  CollaboratorHistoryMoney::create($history_data);
        }
    }
}
