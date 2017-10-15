<?php namespace App\Jobs;

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

//extends Job implements ShouldQueue
class RemoveFieldProductJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $gtin;
    protected $fields;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gtin, $fields)
    {
        $gtin = preg_split("/\\r\\n|\\r|\\n/", $gtin);
        $this->gtin = $gtin;
        $this->fields =           $fields;
//
//        $fields = [
//            'name' => 'Tên sản phẩm',
//            'price' => 'Giá',
//            'image' => 'Ảnh sản phẩm',
//            'category' => 'Danh mục',
//            'ttsp' => 'Thông tin sản phẩm',
//            'cccn' => 'Chứng chỉ và chứng nhận',
//            'ttct' => 'Thông tin công ty',
//            'pbtg' => 'Phân biệt thật giả'
//        ];

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        dd(in_array('name', $this->fields));
        $fields = $this->fields;
        foreach ($this->gtin as $gtin) {
            $product = Product::where('gtin_code', $gtin)->first();
            if (in_array('name', $fields)) {
                $product->product_name = null;
                $product->save();
            }
            if (in_array('price', $fields)) {
                $product->price_default = null;
                $product->save();
            }
            if (in_array('image', $fields)) {
                $product->image_default = '';
                $product->save();

                $m = PProduct::where('gtin_code', $product->gtin_code)->first();
                if ($m) {

                    $images = $m->attachments;
                    $m->unset('attachments');
                    if ($images != null) {
                        foreach ($images as $key => $img) {
                            if (isset($images[$key]['type'])) {
                                if ($images[$key]['type'] == 'image') {
                                    unset($images[$key]);
                                } else {
                                    $m->push('attachments', $images[$key]);
                                }
                            }

                        }
                    }
                }
            }
            if (in_array('category', $fields)) {
                $product->categories()->sync([]);
                $product->save();
            }
            if (in_array('ttsp', $fields)) {
                $product->attributes()->detach(1);

            }
            if (in_array('cccn', $fields)) {
                $product->attributes()->detach(2);

            }
            if (in_array('ttct', $fields)) {
                $product->attributes()->detach(3);

            }
            if (in_array('pbtg', $fields)) {
                $product->attributes()->detach(4);

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

        }

    }
}
