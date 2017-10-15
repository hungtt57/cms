<?php

namespace App\Http\Controllers\Staff\Management;


use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Enterprise\User;
use App\Models\Icheck\Product\VendorStatistic;
use App\Http\Controllers\Controller;
use App\Models\Icheck\Product\Vendor;
use App\Models\Mongo\Product\PComment as Comment;
use App\Models\Enterprise\LogSearchVendor;
class StatisticalVendorBusinessController extends Controller
{
    public function index(Request $request)
    {
        $vendors = null;
        $errorMessage = null;
        if (auth()->guard('staff')->user()->can('statistical-vendor-business')) {
            $vendors = VendorStatistic::where('gln_code', 'like', '893%');
            if ($request->input('search')) {
                $key = $request->input('search');
                $vendors = $vendors->where('gln_code', 'like', '%' . $key . '%')->orWhere('name', 'like', '%' . $key . '%');
            }
            if ($request->input('sort_by') and $request->input('order')) {
                $vendors = $vendors->orderBy($request->input('sort_by'), $request->input('order', 'asc'));
            }
            if ($request->input('filter-business')) {
                $filter = $request->input('filter-business');
                if ($filter == 1) {
                    $vendors = $vendors->where('signed', 1);
                }
                if ($filter == 2) {
                    $vendors = $vendors->where('signed', 0);
                }

            }
            $vendors = $vendors->paginate(20);
        } else {

            if (auth()->guard('staff')->user()->can('search-statistical-vendor-business')) {

                if ($request->input('search')) {
                    $email = auth('staff')->user()->email;
                    $quota_search =  auth('staff')->user()->quota_search;
                    $key = $request->input('search');
                    $log = LogSearchVendor::firstOrCreate(['email' => $email,'key' => $key]);
                    $start = Carbon::now()->startOfDay();
                    $end = Carbon::now()->endOfDay();
                    $count = LogSearchVendor::where('email',$email)->whereDate('createdAt','<=',$end)->whereDate('createdAt','>=',$start)->count();

                    if($count > $quota_search){
                        $errorMessage = 'Bạn đã quá số lần search trong 1 ngày.Vui lòng quay lại vào ngày mai';
                        return view('staff.management.statistical_vendor_business.index', compact('vendors','errorMessage'));
                    }
                    $vendors = VendorStatistic::where('gln_code', 'like', '893%');
                    $key = $request->input('search');
                    $vendors = $vendors->where('gln_code', 'like', '%' . $key . '%')->orWhere('name', 'like', '%' . $key . '%');
                    if ($request->input('sort_by') and $request->input('order')) {
                        $vendors = $vendors->orderBy($request->input('sort_by'), $request->input('order', 'asc'));
                    }
                    if ($request->input('filter-business')) {
                        $filter = $request->input('filter-business');
                        if ($filter == 1) {
                            $vendors = $vendors->where('signed', 1);
                        }
                        if ($filter == 2) {
                            $vendors = $vendors->where('signed', 0);
                        }

                    }
                    $vendors = $vendors->paginate(20);
                }

            } else {
                abort(403);
            }
        }

        return view('staff.management.statistical_vendor_business.index', compact('vendors','errorMessage'));
    }

    public function productByVendor(Request $request, $gln)
    {
        if (auth()->guard('staff')->user()->cannot('statistical-product-by-vendor')) {
            abort(403);
        }
        $vendor = Vendor::where('gln_code', $gln)->first();
        $products = null;
        if ($vendor) {
            $products = $vendor->products()->paginate(20);
            foreach ($products as $product) {
                $images = [];
                if ($product->image_default) {
                    $images[] = get_image_url($product->image_default);
                }

                if ($product->pproduct && isset($product->pproduct->attachments)) {

                    foreach ($product->pproduct->attachments as $value) {

                        if (isset($value['type'])) {
                            if ($value['type'] == 'image') {
                                $images[] = get_image_url($value['link']);

                            }
                        }
                    }

                }
                $product->images = $images;
            }
        }
        return view('staff.management.statistical_vendor_business.product_by_vendor', compact('products'));
    }

    public function commentByVendor(Request $request, $gtin)
    {
        if (auth()->guard('staff')->user()->cannot('statistical-comment-by-vendor')) {
            abort(403);
        }
        $comments = Comment::where('object_id', '=', $gtin)->where('parent', '=', '')->orderBy('createdAt', 'desc')->simplePaginate(30);


        return view('staff.management.statistical_vendor_business.comment', compact('comments'));
    }
}
