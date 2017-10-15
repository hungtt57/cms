<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Illuminate\Support\Facades\Log;
use Mail;
use Carbon\Carbon;
use App\Models\Icheck\Product\Product;
use App\Models\Enterprise\LowQualityProduct;
use App\Models\Enterprise\GtinLogScanNotFound;

class ReportData extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $createBy;
    public $jobName;
    public function __construct($email = '')
    {
        $this->createBy =$email;
        $this->jobName ='Tạo report cho hươngcm';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{

        $now = Carbon::now();
        $fp = fopen(storage_path('app/report-hcm.csv'), 'w');

        //TOng so ma tren he thong

        fputcsv($fp, ['', '> 999', "<= 999, > 499", "<= 499, > 99", "<= 99, > 0",'0']);
        // tổng số mã trên hệ thống
        $tb_999 = Product::where('scan_count','>',999)->count();
        $tb_999_499 =  Product::where('scan_count','<=',999)->where('scan_count','>',499)->count();
        $tb_499_99 =  Product::where('scan_count','<=',499)->where('scan_count','>',99)->count();
        $tb_99_0 =  Product::where('scan_count','<=',99)->where('scan_count','>',0)->count();
        $tb_0 =  Product::where('scan_count',0)->count();
        fputcsv($fp, ['Total barcode', $tb_999, $tb_999_499, $tb_499_99, $tb_99_0, $tb_0]);

        // mã có đủ thông tin tên ảnh categories
        $tac_999 = Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '>', 0)->where('scan_count','>',999)->count();
        $tac_999_499 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '>', 0)->where('scan_count','<=',999)->where('scan_count','>',499)->count();
        $tac_499_99 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '>', 0)->where('scan_count','<=',499)->where('scan_count','>',99)->count();
        $tac_99_0 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '>', 0)->where('scan_count','<=',99)->where('scan_count','>',0)->count();
        $tac_0 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '>', 0)->where('scan_count',0)->count();
        fputcsv($fp, ['have name,have image,have categories', $tac_999, $tac_999_499, $tac_499_99, $tac_99_0, $tac_0]);


        //Các mã có thuộc tính
        $p_999 = Product::has('properties','>',0)->where('scan_count','>',999)->count();
        $p_999_499 =  Product::has('properties','>',0)->where('scan_count','>',499)->count();
        $p_499_99 =  Product::has('properties','>',0)->where('scan_count','>',99)->count();
        $p_99_0 =  Product::has('properties','>',0)->where('scan_count','>',0)->count();
        $p_0 =  Product::has('properties','>',0)->where('scan_count',0)->count();
        fputcsv($fp, ['have properties', $p_999, $p_999_499, $p_499_99, $p_99_0, $p_0]);

        //có tên,có ảnh,chưa có categories
        $tac_999 = Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','>',999)->count();
        $tac_999_499 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','<=',999)->where('scan_count','>',499)->count();
        $tac_499_99 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','<=',499)->where('scan_count','>',99)->count();
        $tac_99_0 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','<=',99)->where('scan_count','>',0)->count();
        $tac_0 =  Product::where('product_name','!=','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count',0)->count();
        fputcsv($fp, ['have name,have image,have not categories', $tac_999, $tac_999_499, $tac_499_99, $tac_99_0, $tac_0]);

        //có tên không ảnh có cate
        $tac_999 = Product::where('product_name','!=','')->where('image_default','')->has('categories', '>', 0)->where('scan_count','>',999)->count();
        $tac_999_499 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '>', 0)->where('scan_count','<=',999)->where('scan_count','>',499)->count();
        $tac_499_99 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '>', 0)->where('scan_count','<=',499)->where('scan_count','>',99)->count();
        $tac_99_0 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '>', 0)->where('scan_count','<=',99)->where('scan_count','>',0)->count();
        $tac_0 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '>', 0)->where('scan_count',0)->count();
        fputcsv($fp, ['have name,have not image,have categories', $tac_999, $tac_999_499, $tac_499_99, $tac_99_0, $tac_0]);

        // có tên,không ảnh không cate
        $tac_999 = Product::where('product_name','!=','')->where('image_default','')->has('categories', '<=', 0)->where('scan_count','>',999)->count();
        $tac_999_499 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '<=', 0)->where('scan_count','<=',999)->where('scan_count','>',499)->count();
        $tac_499_99 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '<=', 0)->where('scan_count','<=',499)->where('scan_count','>',99)->count();
        $tac_99_0 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '<=', 0)->where('scan_count','<=',99)->where('scan_count','>',0)->count();
        $tac_0 =  Product::where('product_name','!=','')->where('image_default','')->has('categories', '<=', 0)->where('scan_count',0)->count();
        fputcsv($fp, ['have name,have not image,have not categories', $tac_999, $tac_999_499, $tac_499_99, $tac_99_0, $tac_0]);

        // không tên,có ảnh,không cate
        $tac_999 = Product::where('product_name','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','>',999)->count();
        $tac_999_499 =  Product::where('product_name','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','<=',999)->where('scan_count','>',499)->count();
        $tac_499_99 =  Product::where('product_name','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','<=',499)->where('scan_count','>',99)->count();
        $tac_99_0 =  Product::where('product_name','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count','<=',99)->where('scan_count','>',0)->count();
        $tac_0 =  Product::where('product_name','')->where('image_default','!=','')->has('categories', '<=', 0)->where('scan_count',0)->count();
        fputcsv($fp, ['have not name,have image,have not categories', $tac_999, $tac_999_499, $tac_499_99, $tac_99_0, $tac_0]);

        //không có thông tin gì
        $tac_999 = Product::where('product_name','')->where('image_default','=','')->has('categories', '<=', 0)->where('scan_count','>',999)->count();
        $tac_999_499 =  Product::where('product_name','')->where('image_default','=','')->has('categories', '<=', 0)->where('scan_count','<=',999)->where('scan_count','>',499)->count();
        $tac_499_99 =  Product::where('product_name','')->where('image_default','=','')->has('categories', '<=', 0)->where('scan_count','<=',499)->where('scan_count','>',99)->count();
        $tac_99_0 =  Product::where('product_name','')->where('image_default','=','')->has('categories', '<=', 0)->where('scan_count','<=',99)->where('scan_count','>',0)->count();
        $tac_0 =  Product::where('product_name','')->where('image_default','=','')->has('categories', '<=', 0)->where('scan_count',0)->count();
        fputcsv($fp, ['Not info', $tac_999, $tac_999_499, $tac_499_99, $tac_99_0, $tac_0]);

        //mã ảnh < 20kb
        $tb_999 = LowQualityProduct::where('scan','>',999)->count();
        $tb_999_499 =  LowQualityProduct::where('scan','<=',999)->where('scan','>',499)->count();
        $tb_499_99 =  LowQualityProduct::where('scan','<=',499)->where('scan','>',99)->count();
        $tb_99_0 =  LowQualityProduct::where('scan','<=',99)->where('scan','>',0)->count();
        $tb_0 =  LowQualityProduct::where('scan',0)->count();
        fputcsv($fp, ['Image < 20kb', $tb_999, $tb_999_499, $tb_499_99, $tb_99_0, $tb_0]);

        //log scan not found
        $tb_999 = GtinLogScanNotFound::where('score','>',999)->count();
        $tb_999_499 =  GtinLogScanNotFound::where('score','<=',999)->where('score','>',499)->count();
        $tb_499_99 =  GtinLogScanNotFound::where('score','<=',499)->where('score','>',99)->count();
        $tb_99_0 =  GtinLogScanNotFound::where('score','<=',99)->where('score','>',0)->count();
        $tb_0 =  GtinLogScanNotFound::where('score',0)->count();
        fputcsv($fp, ['Log scan not found', $tb_999, $tb_999_499, $tb_499_99, $tb_99_0, $tb_0]);

        fclose($fp);

        Mail::raw('', function ($message) use ($now) {
                $message->from('business@icheck.vn', 'iCheck cho doanh nghiệp');
                $message->to('huongcm@icheck.vn', 'Chu Minh Huong');
//                $message->cc('hunguet1471994@gmail.com', 'Trương Tiến Hưng');
                $message->subject('Báo cáo sản phẩm ' . $now);
                $message->attach(storage_path('app/report-hcm.csv'), []);
            }
        );
        }catch(Exception $ex){
            Log::info($ex->getTraceAsString());
         }
    }
}
