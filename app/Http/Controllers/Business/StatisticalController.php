<?php

namespace App\Http\Controllers\Business;

use App\Models\Icheck\Product\DistributorProduct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Icheck\Product\Country;
use App\Models\Enterprise\Product;
use Auth;
use DB;
use Carbon\Carbon;
use App\GALib\AnalyticsLib;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Response;
use App\Models\Enterprise\BusinessChart;
use App\Models\Icheck\Product\Category;
use App\Models\Enterprise\BusinessCategoryChart;
use App\Models\Enterprise\BusinessAge;
use App\Models\Enterprise\BusinessLocation;
use Datatables;
class StatisticalController extends Controller
{
    public function products(Request $request){
        if (auth()->user()->cannot('view-report-product')) {
            abort(403);
        }

        if (!$request->has('date_range')) {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
            $endDate = Carbon::now()->subDays(1)->endOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

        $business = Auth::user();
        $gln = $business->gln()->where('status', GLN::STATUS_APPROVED)->get();
        $gln = $gln->lists('id')->toArray();
        $productsSx = Product::whereIn('gln_id', $gln)->where('status',Product::STATUS_APPROVED)->where('is_quota',1);
        $productPp = $business->productsDistributor(2,1);
        $gtin_sx = clone $productsSx;
        $gtin_sx = $gtin_sx->lists('barcode')->toArray();
        $gtin_pp = clone $productPp;
        $gtin_pp = $gtin_pp->lists('gtin_code')->toArray();
        $array_gtin = array_merge($gtin_sx, $gtin_pp);


        $search = '';
        if ($request->input('search')) {
            $search = $request->input('search');

            $productPp = $productPp->where(function ($query) use ($search) {
                return $query->where('product_name', 'like', '%' . $search . '%')->orWhere('gtin_code', 'like', '%' . $search . '%');
            });

            $productsSx = $productsSx->where(function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')->orWhere('barcode', 'like', '%' . $search . '%');
            });

        }


        //paginate
        $productPp = $productPp->get(['product_name AS name', 'gtin_code','image_default as image']);                                              ;
        $productsSx = $productsSx->get(['name', 'barcode AS gtin_code','image']);
        $array_table = [];
        foreach ($productPp as $pp) {
            $array_table[$pp->gtin_code]['image'] = $pp->image;
            $array_table[$pp->gtin_code]['name'] = $pp->name;
        }
        foreach ($productsSx as $px) {
            $array_table[$px->gtin_code]['name'] = $px->name;
            if($px->image!=''){
                $image =  json_decode($px->image,true);
                if($image && is_array($image)){
                    $array_table[$px->gtin_code]['image'] = $image[0];
                }else{
                    $array_table[$px->gtin_code]['image'] = null;
                }
            }else{
                $array_table[$px->gtin_code]['image'] = null;
            }
        }
        $count = count($array_table);

        $items = collect($array_table);
        $page = $request->get('page', 1);
        $perPage = 10;
        $gtins = new LengthAwarePaginator(
            $items->forPage($page, $perPage), $items->count(), $perPage, $page,['path' => Paginator::resolveCurrentPath()]
        );


        $analytics = new AnalyticsLib();

        //get chart data
        $chartData = [];
        $info = [];

        if($gtins){
            try{

                $info = $analytics->getInfo($startDate, $endDate, $gtins);
            }catch(Exception $ex){

            }
        }

        return view('business.statistical.products', compact('count','search','chartData', 'startDate', 'endDate', 'info', 'gtins'));
    }
    public function getChartData(Request $request){

        if (auth()->user()->cannot('view-report-product')) {
            abort(403);
        }
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDate = Carbon::createFromTimestamp($startDate)->startOfDay()->getTimeStamp();
        $endDate = Carbon::createFromTimestamp($endDate)->startOfDay()->getTimeStamp();
        $business = Auth::user();
        $charts = BusinessChart::where('business_id',$business->id)->where('date','>=',$startDate)->where('date','<=',$endDate)->orderBy('date','asc')->get();
        $result = [];
        foreach ($charts as $chart){
            $result[] = [$chart->date*1000,$chart->total];
        }
        return $result;



    }

    public function categories(Request $request){
        if (auth()->user()->cannot('view-report-category')) {
            abort(403);
        }
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
        $business = Auth::user();
        $categories = $categories->get();


        $category_info =  \DB::table('business_category_chart')
            ->select(\DB::raw('SUM(scan) as scan,SUM(comment) as comment,SUM(`show`) as `show`,SUM(`like`) as `like`,category_id'))
            ->whereBetween('date', [$start, $end])->where('business_id',$business->id)
            ->groupBy('category_id')
            ->get();
        $a = [];
        $pie = [];
        if($category_info){
            foreach ($category_info as $info){
                $a[$info->category_id]['scan'] = $info->scan;
                $a[$info->category_id]['like'] = $info->like;
                $a[$info->category_id]['comment'] = $info->comment;
                $a[$info->category_id]['show'] = $info->show;
                $pie[$info->category_id] = intval($info->scan) +  intval($info->like) +  intval($info->comment) + intval($info->show);
            }
        }

        $category_info = $a;
        $pieChart = [];
        if($pie){
            foreach($categories as $c){
                if(isset($pie[$c->id])){
                    $pieChart[] = ['name' => $c->name,'y' => $pie[$c->id]];
                }

            }
        }

        return view('business.statistical.categories', compact('pieChart', 'startDate', 'endDate', 'category_info', 'categories'));

    }

    public function ages(Request $request){
        if (auth()->user()->cannot('view-report-age')) {
            abort(403);
        }
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
        $business = Auth::user();
        $info_age = BusinessAge::where('business_id',$business->id)->where('date','<=',$end)->where('date','>=',$start)->get()->toArray();
        $pie = [];
        $pie['18-24'] = 0;
        $pie['25-34'] =0;
        $pie['35-44'] = 0;
        $pie['45-54'] = 0;
        $pie['55-64'] =0;
        $pie['65+'] = 0;
        if($info_age){
            foreach ($info_age as $age){
                $pie['18-24'] = $pie['18-24'] + $age['18-24'];
                $pie['25-34'] = $pie['25-34'] + $age['25-34'];
                $pie['35-44'] = $pie['35-44'] + $age['35-44'];
                $pie['45-54'] = $pie['45-54'] + $age['45-54'];
                $pie['55-64'] = $pie['55-64'] + $age['55-64'];
                $pie['65+'] = $pie['65+'] + $age['65+'];
            }
        }
        foreach ($pie as $key => $p){

            $pieChart[] = ['name' => $key,'y' => $p];
        }
        return view('business.statistical.ages', compact('pieChart', 'startDate', 'endDate', '$info_age'));
    }

    public function locations(Request $request){
        if (auth()->user()->cannot('view-report-location')) {
            abort(403);
        }
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
        $business = Auth::user();


        $info_locations =  \DB::table('business_location')
            ->select(\DB::raw('SUM(scan) as scan,SUM(`show`) as `show`,SUM(`like`) as `like`,SUM(comment) as comment,location'))
            ->whereBetween('date', [$start, $end])->where('business_id',$business->id)->where('location','not like','%not set%')
            ->groupBy('location')
            ->paginate(20);

        return view('business.statistical.locations',compact('info_locations','startDate','endDate'));
    }
    public function getLocationData(Request $request){
        if (auth()->user()->cannot('view-report-location')) {
            abort(403);
        }
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
        $business = Auth::user();

        $info_locations =  DB::table('business_location')
            ->select(DB::raw('SUM(scan) as scan,SUM(`show`) as `show`,SUM(`like`) as `like`,SUM(comment) as comment,location'))
            ->whereBetween('date', [$start, $end])->where('business_id',$business->id)->where('location','not like','%not set%')
            ->groupBy('location');

        return Datatables::of($info_locations)->make(true);
    }
}
