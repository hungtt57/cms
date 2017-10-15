<?php namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Collaborator\ContributeProduct;
use DB;
use Carbon\Carbon;
use App\Models\Enterprise\ProductCategory;
use Illuminate\Support\Facades\Log;
use App\Models\Mongo\Product\PProduct;
use App\Models\Icheck\Product\Contribute;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use App\Models\Enterprise\Product as EProduct;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Icheck\Product\ProductInfo;

//extends Job implements ShouldQueue
class AutoSetRelateProductApproveGLN extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $products;
    protected $gln;
    protected $vendor;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products,$gln,$vendor)
    {

        $this->products = $products;
        $this->gln = $gln;
        $this->vendor = $vendor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $v = $this->vendor;
        $gln = $this->gln;
        $products = $this->products;
        $attrs = ProductAttr::lists('id')->toArray();
        DB::beginTransaction();
        try{
            foreach ($products as $product) {
                $newProduct = EProduct::firstOrCreate([
                    'barcode' => $product->gtin_code,
                ]);
                $image_p = null;
                $array_image = [];
                if ($product->image_default != '') {
                    $array_image[] =  $product->image_default;
                }
                if($product->pproduct && isset($product->pproduct->attachments)){

                    foreach ($product->pproduct->attachments as $value){

                        if(isset($value['type'])) {
                            if ($value['type'] == 'image') {
                                $array_image[]  = $value['link'];

                            }
                        }
                    }
                }
                if($array_image){
                    $image_p = json_encode($array_image);
                }

                $data = [
                    'name' => $product->product_name,
                    'image' => $image_p,
                    'price' => $product->price_default,
                    'status' => EProduct::STATUS_APPROVED,
                ];

                $infos = ProductInfo::whereIn('attribute_id', $attrs)->where('product_id', $product->id)->get();
                $infos = $infos->lists('content', 'attribute_id')->toArray();

                $data['attrs'] = $infos;

                $newProduct->update($data);
                $newProduct->gln()->associate($gln->id);

                foreach ($product->categories()->get() as $cat) {
                    ProductCategory::firstOrCreate(['product_id' => $newProduct->id, 'category_id' => $cat->id]);
                }

                $newProduct->save();

                $name = 'prod:' . $newProduct->barcode;
                $iql = 'Product.find({vendor_id:[' . $v->id. ']})';

                $hook = Hook::firstOrCreate(['name' => $name]);
                $hook->iql = $iql;
                $hook->type = 2;
                $hook->save();
                HookProduct::where('hook_id',$hook->id)->delete();
            }
            DB::commit();
        }catch (\Exception $ex){
            Log::info($ex->getTraceAsString());
            DB::rollback();
        }


    }


}
