<?php namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Collaborator\ContributeProduct;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Icheck\Product\Product;
use App\Models\Mongo\Product\PProduct;
use App\Models\Icheck\Product\Contribute;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;

//extends Job implements ShouldQueue
class AddRelateProductPPJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $products;
    protected $pD;
    protected $business;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products, $pD, $business)
    {
        $this->pD = $pD;
        $this->products = $products;
        $this->business = $business;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $business = $this->business;
        $start_date = $business->start_date;
        $end_date = $business->end_date;
        if (empty($start_date) || empty($end_date)) {
            $start_date = Carbon::now()->startOfDay();
            $end_date = Carbon::now()->startOfDay()->addYear(1);
        }
        $products = $this->products;
        $pD = $this->pD;
        $product = Product::find($pD->product_id);


        $name = 'prod:' . $product->gtin_code;
        $hook = Hook::firstOrCreate(['name' => $name]);
        $hook->iql = null;
        $hook->type = 0;
        $hook->save();
        HookProduct::where('hook_id', $hook->id)->delete();
        foreach ($products as $pD) {
            DB::beginTransaction();
            try {
                $p = Product::find($pD->product_id);

                //add splq cho thang product moi
                $hook_product = HookProduct::firstOrCreate(['hook_id'=>$hook->id,'product_id'=>$p->gtin_code]);
                $hook_product->start_date = $start_date;
                $hook_product->end_date = $end_date;
                $hook_product->save();
                //end

                // tim hook cua sp da set
                $pName = 'prod:' . $p->gtin_code;
                $pHook = Hook::firstOrCreate(['name' => $pName]);
                $pHook->iql = null;
                $pHook->type = 0;
                $pHook->save();
                //them sp hook moi
                $pHook_product = HookProduct::firstOrCreate(['hook_id' => $pHook->id,'product_id' => $product->gtin_code]);
                $pHook_product->start_date = $start_date;
                $pHook_product->end_date = $end_date;
                $pHook_product->save();
                DB::commit();
            } catch (\Exception $ex) {
                Log::info($ex->getTraceAsString());
                DB::rollBack();
            }

        }



    }
}
