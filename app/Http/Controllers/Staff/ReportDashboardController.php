<?php

namespace App\Http\Controllers\Staff;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\Product;
use Auth;
use App\GALib\AnalyticsLib;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Models\Enterprise\VendorChart;
use App\Models\Enterprise\CategoryChart;
use DB;
use App\Models\Icheck\Product\Category;
use App\Models\Enterprise\CategoryData;
class ReportDashboardController extends Controller
{

    public function product(Request $request)
    {

        if (!$request->has('date_range')) {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            $endDate = Carbon::now()->subDays(1)->endOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }
        $analytics = new AnalyticsLib();
        $gtins = Product::paginate(10);
        if ($gtins) {
            try {

                $info = $analytics->getInfo($startDate, $endDate, $gtins);
            } catch (Exception $ex) {

            }
        }

        return view('staff.report_dashboard.product', compact('startDate', 'endDate', 'info', 'gtins'));
    }

    public function category(Request $request)
    {

        if (!$request->has('date_range')) {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            $endDate = Carbon::now()->subDays(1)->endOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

        $start = $startDate->getTimeStamp();
        $end = $endDate->getTimeStamp();


        $categories = Category::where('parent_id', 12);

        if ($request->input('search')) {
            $categories = $categories->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $categories = $categories->get();


        $category_info =  \DB::table('category_data')
            ->select(\DB::raw('SUM(scan) as scan,SUM(comment) as comment,SUM(`like`) as `like`,category_id'))
            ->whereBetween('date', [$start, $end])
            ->groupBy('category_id')
            ->get();
        $a = [];
        $pie = [];

        foreach ($category_info as $info){
            $a[$info->category_id]['scan'] = $info->scan;
            $a[$info->category_id]['like'] = $info->like;
            $a[$info->category_id]['comment'] = $info->comment;
            $pie[$info->category_id] = intval($info->scan) +  intval($info->like) +  intval($info->comment);
        }
        $category_info = $a;
        $pieChart = [];
        if($pie){
            foreach($categories as $c){

                $pieChart[] = ['name' => $c->name,'y' => $pie[$c->id]];
            }
        }


        return view('staff.report_dashboard.category', compact('pieChart', 'startDate', 'endDate', 'category_info', 'categories'));
    }


    public function categoryDetail($id, Request $request)
    {

        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(7)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }



        return view('staff.report_dashboard.category_detail', compact('date', 'startDate', 'endDate', 'pro_show', 'pro_scan', 'pro_comment', 'pro_like'));
    }

    public function vendor(Request $request)
    {


        if (!$request->has('date_range')) {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            $endDate = Carbon::now()->subDays(1)->endOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

        $start = $startDate->getTimeStamp();
        $end = $endDate->getTimeStamp();

        $charts = \DB::table('vendor_chart')
            ->select(\DB::raw('SUM(total) as total, date'))
            ->whereBetween('date', [$start, $end])
            ->groupBy('date')
            ->get();

        $result = [];
        foreach ($charts as $chart) {
            $result[] = [$chart->date * 1000, intval($chart->total)];
        }
        $chartData = $result;

        $vendors = Vendor::paginate(20);
        $analytics = new AnalyticsLib();
        if ($vendors) {
            try {
                $vendor_info = $analytics->getInfoReportVendor($startDate, $endDate, $vendors);
            } catch (\Exception $ex) {

            }
        }
        return view('staff.report_dashboard.vendor', compact('chartData', 'startDate', 'endDate', 'vendor_info', 'vendors'));
    }

    public function vendorDetail(Request $request, $id)
    {

        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(7)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }


        $start = $startDate->getTimeStamp();
        $end = $endDate->getTimeStamp();

        $charts = VendorChart::where('vendor_id', $id)->where('date', '>=', $start)->where('date', '<=', $end)->orderBy('date', 'asc')->get();


        $result = [];
        foreach ($charts as $chart) {
            $result[] = [$chart->date * 1000, intval($chart->total)];
        }
        $chartData = $result;


        $product = Product::where('vendor_id', $id);
        if ($request->input('search')) {
            $search = $request->input('search');
            $product = $product->where('product_name', 'like', '%' . $search . '%')->orWhere('gtin_code', 'like', '%' . $search . '%');
        }
        $products = $product->paginate(20);
        $gtin = clone $products;
        $gtins = $gtin->lists('gtin_code')->toArray();

        $analytics = new AnalyticsLib();
        $info = [];
        if ($gtins) {

            try {
                $info = $analytics->getInfoProduct($startDate, $endDate, $gtins);
            } catch (Exception $ex) {

            }
        }


        return view('staff.report_dashboard.vendor_detail', compact('chartData', 'products', 'startDate', 'endDate', 'info'));
    }
}
