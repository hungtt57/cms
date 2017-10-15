<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Collaborator\ContributeProduct as Product;
use App\Models\Social\Product as SProduct;
use DB;
use Mail;
use Carbon\Carbon;

class ExportHCMJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        $fp = fopen(storage_path('app/tong_hop.csv'), 'w');
        fputcsv($fp, ['', '> 999', "<= 999, > 499", "<= 499, > 99", "<= 99, > 0",'= 0']);

        $gt999 = DB::connection('social_view')->table('v_hcm_count_full_gt_999')->count();
        $gt499 = DB::connection('social_view')->table('v_hcm_count_full_999_500')->count();
        $gt99 = DB::connection('social_view')->table('v_hcm_count_full_499_100')->count();
        $gt0 = DB::connection('social_view')->table('v_hcm_count_full_99_1')->count();
        $eq0 = DB::connection('social_view')->table('v_hcm_count_full_0')->count();

        fputcsv($fp, ['Full info', $gt999, $gt499, $gt99, $gt0, $eq0]);

        $gt999 = DB::connection('social_view')->table('v_hcm_count_no_gt_999')->count();
        $gt499 = DB::connection('social_view')->table('v_hcm_count_no_999_500')->count();
        $gt99 = DB::connection('social_view')->table('v_hcm_count_no_499_100')->count();
        $gt0 = DB::connection('social_view')->table('v_hcm_count_no_99_1')->count();
        $eq0 = DB::connection('social_view')->table('v_hcm_count_no_0')->count();

        fputcsv($fp, ['No info', $gt999, $gt499, $gt99, $gt0, $eq0]);

        fclose($fp);

        $fp = fopen(storage_path('app/40k_khong_co_thong_tin.csv'), 'w');
        fputcsv($fp, ['gtin', 'scanCount']);

        $no40k_c = DB::connection('social_view')->table('v_hcm_no_40k')->select(['gtin_code', 'scan_count'])->chunk(500, function ($p) use ($fp) {
            foreach ($p as $product) {
                fputcsv($fp, [$product->gtin_code, $product->scan_count]);
            }
        });

        fclose($fp);


        $fp = fopen(storage_path('app/khong_ten-co_anh-khong_cate.csv'), 'w');
        fputcsv($fp, ['gtin', 'scanCount']);

        $no40k_c = DB::connection('social_view')->table('v_hcm_i')->select(['gtin_code', 'scan_count'])->chunk(500, function ($p) use ($fp) {
            foreach ($p as $product) {
                fputcsv($fp, [$product->gtin_code, $product->scan_count]);
            }
        });

        fclose($fp);


        $fp = fopen(storage_path('app/co_ten-khong_anh-co_cate.csv'), 'w');
        fputcsv($fp, ['gtin', 'scanCount']);

        $no40k_c = DB::connection('social_view')->table('v_hcm_n_c')->select(['gtin_code', 'scan_count'])->chunk(500, function ($p) use ($fp) {
            foreach ($p as $product) {
                fputcsv($fp, [$product->gtin_code, $product->scan_count]);
            }
        });

        fclose($fp);


        $fp = fopen(storage_path('app/co_ten-co_anh-khong_cate.csv'), 'w');
        fputcsv($fp, ['gtin', 'scanCount']);

        $no40k_c = DB::connection('social_view')->table('v_hcm_n_i')->select(['gtin_code', 'scan_count'])->chunk(500, function ($p) use ($fp) {
            foreach ($p as $product) {
                fputcsv($fp, [$product->gtin_code, $product->scan_count]);
            }
        });

        fclose($fp);


        $fp = fopen(storage_path('app/co_ten-khong_anh-khong_cate.csv'), 'w');
        fputcsv($fp, ['gtin', 'scanCount']);

        $no40k_c = DB::connection('social_view')->table('v_hcm_n')->select(['gtin_code', 'scan_count'])->chunk(500, function ($p) use ($fp) {
            foreach ($p as $product) {
                fputcsv($fp, [$product->gtin_code, $product->scan_count]);
            }
        });

        fclose($fp);

        Mail::raw('', function ($message) use ($now) {
                $message->from('business@icheck.vn', 'iCheck cho doanh nghiệp');
                $message->to('huongcm@icheck.vn', 'Chu Minh Huong');
                $message->cc('huytq@icheck.vn', 'Huy TQ');
                $message->subject('Báo cáo sản phẩm ' . $now);
                $message->attach(storage_path('app/tong_hop.csv'), []);
                $message->attach(storage_path('app/40k_khong_co_thong_tin.csv'), []);
                $message->attach(storage_path('app/khong_ten-co_anh-khong_cate.csv'), []);
                $message->attach(storage_path('app/co_ten-khong_anh-co_cate.csv'), []);
                $message->attach(storage_path('app/co_ten-co_anh-khong_cate.csv'), []);
                $message->attach(storage_path('app/co_ten-khong_anh-khong_cate.csv'), []);
            }
        );
    }
}
