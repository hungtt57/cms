<?php

namespace App\Jobs;

use DB;
use App\Jobs\Job;
use App\Models\Icheck\Product\SearchNotFound;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Collaborator\ContributeProduct as Product;
use App\Models\Collaborator\SearchResult;
//use App\Models\Social\Product as SProduct;
use GuzzleHttp\Client;
use App\Models\Icheck\Product\Product as SProduct;
use App\Models\Icheck\Product\Vendor as SocialVendor;
use Carbon\Carbon;
use App\Models\Enterprise\GtinLogScanNotFound;
use App\Models\Enterprise\LowQualityProduct;


use App\Jobs\SubImportContributeProductJob;
use Illuminate\Support\Facades\Log;
//extends Job implements ShouldQueue
class ImportContributeProductJob extends Job implements ShouldQueue

{
    use InteractsWithQueue, SerializesModels;

    protected $data;
    public $createBy;
    public $jobName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email)
    {
        $this->data = $data;
        $this->createBy = $email;
        $this->jobName = "Add list sản phẩm cho ctv";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        1 => 'Không có tên',
//            2 => 'LOG SCAN NOT FOUND',
//            3 => 'Có tên,không có ảnh,không danh mục',
//            4 => 'Có tên,có ảnh,không có danh mục',
//            5 => 'Có tên,không ảnh,có danh mục',
//            6 => 'Nhập danh sách mã',
//            7 => 'Mã kém chất lượng'
        ini_set('memory_limit', '2048M');

        $data = $this->data;
        $type = $data['type'];
        $gtin = $data['gtin'];
        $gln = $data['gln'];
        $quantity = $data['quantity'];
        $group = $data['group'];

        if ($type == 6) {

            if ($gln) {
                $gln = preg_split("/\\r\\n|\\r|\\n/", $gln);
                $vendorIds = SocialVendor::select(['id'])->whereIn('gln_code', $gln)->get()->lists('id')->toArray();
                $gtin = SProduct::select(['gtin_code'])->whereIn('vendor_id', $vendorIds)->where('searched',1)->get();
                foreach ($gtin->chunk(1000) as $gtins){
                    dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
                }
            } else {
                $gtin = preg_split("/\\r\\n|\\r|\\n/", $gtin);
                $gtin = SProduct::select(['gtin_code'])->whereIn('gtin_code', $gtin)->where('searched',1)->get();
                foreach ($gtin->chunk(1000) as $gtins){
                    dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
                }

            }
        } elseif ($type == 1) {

            $gtin = SProduct::where('product_name', '')->where('searched',1)->orderBy('scan_count', 'desc')->limit($quantity)->get();
            foreach ($gtin->chunk(1000) as $gtins){
                dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
            }
        } elseif ($type == 3) {
            $products = SProduct::has('categories', '<=', 0);
            $products = $products->where('product_name', '!=', '');
            $products = $products->where(function ($query) {
                $query->whereNull('image_default')
                    ->orWhere('image_default', '');
            });
            $gtin = $products->orderBy('scan_count', 'desc')->limit($quantity)->get();

            foreach ($gtin->chunk(1000) as $gtins){
                dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
            }
        } elseif ($type == 4) {

            $products = SProduct::has('categories', '<=', 0);
            $products = $products->where('product_name', '!=', '');
            $products = $products->where('image_default', '!=', '');
            $gtin = $products->orderBy('scan_count', 'desc')->limit($quantity)->get();
            foreach ($gtin->chunk(1000) as $gtins){
                dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
            }
        } elseif ($type == 5) {
            $products = SProduct::has('categories', '>', 0);
            $products = $products->where('product_name', '!=', '');
            $products = $products->where(function ($query) {
                $query->whereNull('image_default')
                    ->orWhere('image_default', '');
            });
            $gtin = $products->orderBy('scan_count', 'desc')->limit($quantity)->get();
            foreach ($gtin->chunk(1000) as $gtins){
                dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
            }
        } elseif ($type == 7) {
            $gtin = LowQualityProduct::limit($quantity)->get();
            foreach ($gtin->chunk(1000) as $gtins){
                dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
            }
        } elseif ($type == 2) {

            $gtin = GtinLogScanNotFound::where('status', 0)->orderBy('score', 'desc')->limit($quantity)->get();
            foreach ($gtin->chunk(1000) as $gtins){
                dispatch(new SubImportContributeProductJob($gtins->lists('gtin_code')->toArray(), $type,$group, $this->createBy));
            }
        }

    }
}
