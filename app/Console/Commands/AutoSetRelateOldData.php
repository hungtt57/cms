<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\GLN;
use Carbon\Carbon;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use Illuminate\Support\Facades\Log;
use App\Models\Enterprise\ProductDistributor;
//
use App\Models\Enterprise\Product;
use App\Models\Icheck\Product\Product as Product2;
use App\Models\Icheck\Product\VendorStatistic;
class AutoSetRelateOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        date_default_timezone_set('Asia/Saigon');
//        $businesses = Business::where('status', Business::STATUS_ACTIVATED)->get();
//        if ($businesses) {
//            foreach ($businesses as $business) {
//                $this->line('Start : ' . $business->id);
//                $start_date = $business->start_date;
//                $end_date = $business->end_date;
//                if (empty($start_date) || empty($end_date)) {
//                    $start_date = Carbon::now()->startOfDay();
//                    $end_date = Carbon::now()->startOfDay()->addYear(1);
//                }

                //set splq sx
//                $glns = $business->gln()->where('status', GLN::STATUS_APPROVED)->get();
//                if ($glns) {
//                    foreach ($glns as $gln) {
//                        if ($gln) {
//                            $products = Product::where('gln_id',$gln->id)->get();
//                            if ($products) {
//                                DB::beginTransaction();
//                                try {
//                                    foreach ($products as $product) {
//                                        if($product->relate_product == 1) {
//                                                $start_date = $business->start_date;
//                                                $end_date = $business->end_date;
//                                                if ($start_date and $end_date) {
//                                                    $name = 'prod:' . $product->barcode;
//                                                    $hook = Hook::firstOrCreate(['name' => $name]);
//                                                    $hook->iql = null;
//                                                    $hook->type = 0;
//                                                    $hook->save();
//                                                    HookProduct::where('hook_id', $hook->id)->delete();
//                                                    $list_gtin = Product::where('gln_id', $product->gln_id)->where('is_exist', 1)->get()->lists('barcode');
//                                                    foreach ($list_gtin as $gtin) {
//                                                        $hook_product = HookProduct::firstOrCreate(['hook_id' => $hook->id, 'product_id' => $gtin]);
//                                                        $hook_product->start_date = Carbon::createFromTimestamp(strtotime($start_date));
//                                                        $hook_product->end_date = Carbon::createFromTimestamp(strtotime($end_date));
//                                                        $hook_product->save();
//                                                    }
//                                                }
//                                                $product->relate_product = 0;
//                                                $product->save();
//                                        }
//                                    }
//                                    DB::commit();
//                                } catch (\Exception $ex) {
//                                    $this->line('Failt with sx: '.$business->id);
//                                    Log::info($ex->getTraceAsString());
//                                    DB::rollback();
//                                }
//                            }
//
//                        }
//
//
//                    }
//                }
                //end set splq
//                $pDs = ProductDistributor::where('business_id', $business->id)->where('is_first', 1)->get();
//                if ($pDs) {
//
//                    foreach ($pDs as $pD) {
//                        $p = Product2::find($pD->product_id);
//                        if ($p) {
//                            $productEx = ProductDistributor::where('business_id', $business->id)->where('is_first', 1)->where('product_id', '!=', $p->product_id)->get();
//                            DB::beginTransaction();
//                            try {
//                                if ($productEx) {
//                                    $name = 'prod:' . $p->gtin_code;
//                                    $hook = Hook::firstOrCreate(['name' => $name]);
//                                    $hook->iql = null;
//                                    $hook->type = 0;
//                                    $hook->save();
//                                    HookProduct::where('hook_id', $hook->id)->delete();
//
//                                    foreach ($productEx as $pEx) {
//                                        $productNew = Product2::find($pEx->product_id);
//
//                                        //add splq cho thang product moi
//                                        $hook_product = HookProduct::firstOrCreate(['hook_id' => $hook->id, 'product_id' => $productNew->gtin_code]);
//                                        $hook_product->start_date = $start_date;
//                                        $hook_product->end_date = $end_date;
//                                        $hook_product->save();
//                                        //end
//
//                                        // tim hook cua sp da set
//                                        $pName = 'prod:' . $productNew->gtin_code;
//                                        $pHook = Hook::firstOrCreate(['name' => $pName]);
//                                        $pHook->iql = null;
//                                        $pHook->type = 0;
//                                        $pHook->save();
//                                        //them sp hook moi
//                                        $pHook_product = HookProduct::firstOrCreate(['hook_id' => $pHook->id, 'product_id' => $p->gtin_code]);
//                                        $pHook_product->start_date = $start_date;
//                                        $pHook_product->end_date = $end_date;
//                                        $pHook_product->save();
//
//
//                                    }
//                                }
//                                DB::commit();
//
//                            } catch (\Exception $ex) {
//                                $this->line('Failt with pp: ' . $business->id);
//                                DB::rollBack();
//
//                            }
//                        }
//
//
//                    }
//                }
//                $this->line('Success with : ' . $business->id);

//            }


//        }
        GLN::where('status',GLN::STATUS_APPROVED)->chunk(500,function($glns){
            foreach ($glns as $gln){
                $vendor = VendorStatistic::where('gln_code',$gln->gln)->first();
                if($vendor){
                    $vendor->signed = 1;
                    $vendor->save();
                    $this->line('Succecc : '.$gln->gln);
                }

            }
        });

    }
}
