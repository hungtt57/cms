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
use App\Models\Enterprise\ProductDistributor;
use DB;
//extends Job implements ShouldQueue
class AddListProductDistributor extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $gtin;
    protected $id;
    public $createBy;
    public $jobName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($gtin, $id,$email)
    {
        $gtin = preg_split("/\\r\\n|\\r|\\n/", $gtin);
        $this->gtin = $gtin;
        $this->id = $id;
        $this->createBy = $email;
        $this->jobName = 'Thêm danh sách sản phẩm phân phối';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $gtins= $this->gtin;
        $id = $this->id;
        DB::beginTransaction();

        foreach ($gtins as $gtin){
            try{
                $is_first = 0;
                $product = Product::where('gtin_code',$gtin)->first();
                if($product){
                    $count = ProductDistributor::where('product_id',$product->id)->where('is_first',1)->count();

                    if($count == 0){
                        $is_first = 1;
                    }
                    $pD = ProductDistributor::firstOrCreate(['business_id'=>$id,'product_id' => $product->id]);

                    if($pD->is_first!=1){
                        $pD->is_first = $is_first;
                    }

                    $pD->status=ProductDistributor::STATUS_ACTIVATED;
                    $pD->save();
                }

                DB::commit();
            }catch(\Exception $ex){
                DB::rollBack();
            }

        }

    }
}
