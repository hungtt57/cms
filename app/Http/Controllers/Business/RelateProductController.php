<?php

namespace App\Http\Controllers\Business;

use App\Models\Icheck\Product\DistributorProduct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\MStaffNotification;
use App\Models\Icheck\Product\Country;
use App\Models\Enterprise;
use Auth;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\Product as SProduct;
use App\Models\Enterprise\Product;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use App\Models\Enterprise\ProductDistributor;
use Carbon\Carbon;
use DB;
use Response;

class RelateProductController extends Controller
{
//    public function listSx(Request $request, $gtin)
//    {
//        if (auth()->user()->cannot('view-relate-product')) {
//            abort(403);
//        }
//        $productHT = SProduct::where('gtin_code', $gtin)->first();
//        if (empty($productHT)) {
//            return redirect()->back()->with('error', 'MÃ sản phẩm này chưa tồn tại trên hệ thống!');
//        }
//        $business = Auth::user();
//        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
//        $gln = $gln->lists('id')->toArray();
//        $productsEx = Product::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();
//        $product = Product::where('barcode', $gtin)->first();
//        if (!in_array($product->barcode, $productsEx)) {
//            return redirect()->route('Business::product@index')->with('error', 'Bạn không có quyền xem sản phẩm này!!');
//        }
//
//        if ($product->is_quota == 0) {
//            return redirect()->route('Business::product@index')->with('error', 'Bạn không có quyền xem sản phẩm này!!');
//        }
//        $products = null;
//        $hook = Hook::where('name', 'like', '%' . $gtin)->first();
//        if (!empty($hook)) {
//            $iql = $hook->iql;
//            $len = strlen($iql) - 28;
//            $hVendor = substr($iql, 25, $len);
//            $hVendor = explode(',', $hVendor);
//            $products = SProduct::whereIn('vendor_id', $hVendor)->paginate(10);
//        }
//
//        return view('business.relate_product.listSx', compact('products', 'product', 'hook'));
//    }
//
//    public function removeSx(Request $request)
//    {
//        $gtin = $request->input('gtin');
//
//        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
//        $gln = $gln->lists('id')->toArray();
//        $productsEx = Product::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();
//
//        if (!in_array($gtin, $productsEx)) {
//            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
//        }
//
//
//        $product = Product::where('barcode', $gtin)->first();
//        if ($product) {
//            $product->relate_product = 0;
//            $product->save();
//        } else {
//            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
//        }
//        $hook = Hook::where('name', 'like', '%' . $gtin);
//        $clone = clone $hook;
//
//        if ($clone->first()) {
//            $hook->delete();
//            return redirect()->route('Business::product@index')->with('success', 'Xóa sản phẩm liên quan thành công');
//        }
//    }
//
//    public function addSx(Request $request)
//    {
//        $gtin = $request->input('gtin');
//
//
//        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
//        $gln = $gln->lists('id')->toArray();
//        $productsEx = Product::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();
//
//        if (!in_array($gtin, $productsEx)) {
//            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
//        }
//
//        $product = SProduct::where('gtin_code', $gtin)->first();
//        if ($product) {
//            $name = 'prod:' . $gtin;
//            $iql = 'Product.find({vendor_id:[' . $product->vendor_id . ']})';
//
//            $hook = Hook::firstOrCreate(['name' => $name]);
//            $hook->iql = $iql;
//            $hook->type = 2;
//            $hook->save();
//
//            HookProduct::where('hook_id', $hook->id)->delete();
//            $productDN = Product::where('barcode', $gtin)->first();
//            $productDN->relate_product = 1;
//            $productDN->save();
//            return redirect()->back()->with('success', 'Thêm thành công');
//        }
//        return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
//
//
//    }


    public function listPp(Request $request, $gtin)
    {
        if (auth()->user()->cannot('view-relate-product')) {
            abort(403);
        }
        $business = Auth::user();
        $product = SProduct::where('gtin_code', $gtin)->first();
        if (empty($product)) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }

        $pD2 = ProductDistributor::where('business_id', $business->id)->where('product_id', $product->id)->first();
        if (empty($pD2) || $pD2->is_quota != 1) {
            return redirect()->back()->with('error', 'Sản phẩm chưa được đăng kí hoặc không có quyền xem');
        }
        if (empty($pD2) || $pD2->is_first != 1) {
            return redirect()->back()->with('error', 'Sản phẩm chưa được đăng kí hoặc có quyền sửa');
        }


        $products = null;
        $hook = Hook::where('name', 'like', '%' . $gtin)->first();


        if (!empty($hook)) {
            $hook_product = HookProduct::where('hook_id', $hook->id)->orderBy('id', 'asc')->get()->lists('product_id');
            $product_related = SProduct::whereIn('gtin_code', $hook_product)->get()->lists('gtin_code')->toArray();
        }

        $products = ProductDistributor::where('business_id', $business->id)->where('is_first', 1)->where('product_id', '!=', $product->id);

        if ($request->input('q')) {

            $sp = SProduct::where(function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('gtin_code', 'like', '%' . $request->input('q') . '%');
            })->lists('id')->toArray();
            $products = $products->whereIn('product_id', $sp);
        }
        if ($request->input('filter') and $request->input('filter') != 1) {
            $filter = $request->input('filter');
            //co lien quan
            if ($filter == 2) {
                $p = SProduct::whereIn('gtin_code', $product_related)->get()->lists('id')->toArray();
                $products = $products->whereIn('product_id', $p);
            }
            if ($filter == 3) {
                $p = SProduct::whereIn('gtin_code', $product_related)->get()->lists('id')->toArray();
                $products = $products->whereNotIn('product_id', $p);
            }
        }
        $products = $products->paginate(10);
        return view('business.relate_product.listPp', compact('products', 'product', 'hook', 'product_related'));
    }

    public function removePP(Request $request)
    {
        $gtin = $request->input('gtin_code');
        $gtin2 = $request->input('gtin_code2');

        $business = Auth::user();
        $product = SProduct::where('gtin_code', $gtin)->first();
        if (empty($product)) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }
        $pD = ProductDistributor::where('business_id', $business->id)->where('product_id', $product->id)->first();
        if (empty($pD) || $pD->is_first != 1) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }
        $hook = Hook::where('name', 'like', '%' . $gtin)->first();
        if ($hook) {
            HookProduct::where('hook_id', $hook->id)->where('product_id', $gtin2)->delete();
        }
        return redirect()->back()->with('success', 'Hủy thành công');
    }

    public function addPp(Request $request)
    {
        $gtin = $request->input('gtin_code');
        $gtin2 = $request->input('gtin_code2');

        $business = Auth::user();
        $product = SProduct::where('gtin_code', $gtin)->first();
        $product2 = SProduct::where('gtin_code', $gtin2)->first();
        if (empty($product)) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }
        if (empty($product2)) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }
        $pD = ProductDistributor::where('business_id', $business->id)->where('product_id', $product->id)->first();

        if (empty($pD) || $pD->is_first != 1) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }

        $pD2 = ProductDistributor::where('business_id', $business->id)->where('product_id', $product2->id)->first();

        if (empty($pD2) || $pD2->is_first != 1) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }

        $hook = Hook::where('name', 'like', '%' . $gtin)->first();
        if ($hook) {
            $pHook_product = HookProduct::firstOrCreate(['hook_id' => $hook->id, 'product_id' => $gtin2]);
            $start_date = $business->start_date;
            $end_date = $business->end_date;
            if (empty($start_date) || empty($end_date)) {
                $start_date = Carbon::now()->startOfDay();
                $end_date = Carbon::now()->startOfDay()->addYear(1);
            }
            $pHook_product->start_date = $start_date;
            $pHook_product->end_date = $end_date;
            $pHook_product->save();
            return redirect()->back()->with('success', 'Thêm thành công');
        }
        return redirect()->back()->with('error', 'Không thành công');
    }


    public function updatePp(Request $request)
    {
        $gtin = $request->input('gtin_code');
        $gtin_update = $request->input('gtin_update');
        $gtin_update = explode(',', $gtin_update);
        $business = Auth::user();
        $product = SProduct::where('gtin_code', $gtin)->first();

        if (empty($product)) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }
        $pD = ProductDistributor::where('business_id', $business->id)->where('product_id', $product->id)->first();

        if (empty($pD) || $pD->is_first != 1) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm hoặc doanh nghiệp không có quyền!');
        }

        $hook = Hook::where('name', 'like', '%' . $gtin)->first();
        if ($hook) {
            DB::beginTransaction();
            $start_date = $business->start_date;
            $end_date = $business->end_date;
            if (empty($start_date) || empty($end_date)) {
                $start_date = Carbon::now()->startOfDay();
                $end_date = Carbon::now()->startOfDay()->addYear(1);
            }
            try {
                HookProduct::where('hook_id', $hook->id)->delete();
                foreach ($gtin_update as $gtin) {
                    $product2 = SProduct::where('gtin_code', $gtin)->first();

                    if (empty($product2)) {
                        continue;
                    }

                    $pD2 = ProductDistributor::where('business_id', $business->id)->where('product_id', $product2->id)->first();
                    if (empty($pD2) || $pD2->is_first != 1) {
                        continue;
                    }
                    $pHook_product = HookProduct::firstOrCreate(['hook_id' => $hook->id, 'product_id' => $gtin]);
                    $pHook_product->start_date = $start_date;
                    $pHook_product->end_date = $end_date;
                    $pHook_product->save();

                }
                DB::commit();
                return redirect()->back()->with('success', 'Update thành công');
            } catch (\Exception $ex) {

                DB::rollback();
                return redirect()->back()->with('error', 'Hệ thống có lỗi! Vui lòng thử lại sau');
            }

        }


    }


    public function listRelateProduct(Request $request, $gtin)
    {
        if (auth()->user()->cannot('view-relate-product')) {
            abort(403);
        }
        $productHT = SProduct::where('gtin_code', $gtin)->first();
        if (empty($productHT)) {
            return redirect()->back()->with('error', 'MÃ sản phẩm này chưa tồn tại trên hệ thống!');
        }
        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
        $gln = $gln->lists('id')->toArray();
        $productsEx = Product::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();
        $product = Product::where('barcode', $gtin)->first();
        if(!$product){
            return redirect()->route('Business::product@index')->with('error', 'MÃ sản phẩm này không thuộc sở hữu của bạn!');
        }
        if (!in_array($product->barcode, $productsEx)) {
            return redirect()->route('Business::product@index')->with('error', 'Bạn không có quyền xem sản phẩm này!!');
        }

        if ($product->is_quota == 0) {
            return redirect()->route('Business::product@index')->with('error', 'Bạn không có quyền xem sản phẩm này!!');
        }
        $input_gtin = $gtin;
        if ($request->input('type') != null) {
            $type = $request->input('type');
            if ($type == 1) {
                //theo san pham
                if ($request->input('search')) {

                    $products = Product::whereIn('gln_id', $gln)->where(function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->input('search') . '%')
                            ->orWhere('barcode', 'like', '%' . $request->input('search') . '%');
                    })->where('is_exist', 1);

                } else {
                    $products = Product::whereIn('gln_id', $gln)->where('barcode', '!=', $input_gtin);
                }

            } elseif ($type == 2) {
                // cung vendor
                $products = Product::where('gln_id', $product->gln_id)->where('barcode', '<>', $input_gtin);
            }
        } else {
            $products = Product::where('gln_id', $product->gln_id)->where('barcode', '<>', $input_gtin);
        }

        $hook = Hook::where('name', 'like', '%' . $input_gtin)->first();
        $list_gtin = [];
        $hookId = null;
        if (!empty($hook)) {
            $hookId = $hook->id;
            if ($hook->type == 0) {
                $hook_product = HookProduct::where('hook_id', $hook->id)->orderBy('id', 'asc')->get();
                $list_gtin = clone $hook_product;
                $list_gtin = $list_gtin->lists('product_id')->toArray();
            }
        }
        $products = $products->whereNotIn('barcode', $list_gtin)->orderBy('id', 'asc')->paginate(50);
        return view('business.relate_product.listRelateProduct', compact('products', 'hook_product', 'hookId', 'gtin'));

    }

    public function updateRelateProduct(Request $request)
    {

        $business = auth()->user();
        $startDate = $business->start_date;
        $endDate = $business->end_date;
        $data = $request->all();
        if ($data['gtin_codes'] and $startDate and $endDate) {
            $id = $data['id'];
            if(empty($id)){
                $name = 'prod:' .$data['gtin_code'];
                $hook = Hook::firstOrCreate(['name' => $name]);
                $hook->iql = null;
                $hook->type = 0;
                $hook->save();
            }else{
                $hook = Hook::find($id);
            }
            if (empty($hook)) {
                return Response::json(['error' => 'Có lỗi xảy ra vui lòng thử lại'], 404);
            }
            if($hook->type != 0){
                return Response::json(['error' => 'Hook không phải set theo sản phẩm'], 404);
            }
            DB::beginTransaction();
            try {
                $gtin_codes = $data['gtin_codes'];
                foreach ($gtin_codes as $gtin) {
                    $hook_product = HookProduct::where('product_id', trim($gtin))->where('hook_id', $hook->id)->first();
                    if ($hook_product) {
                        $hook_product->delete();
                    } else {
                        $hp = new HookProduct();
                        $hp->hook_id = $id;
                        $hp->product_id = trim($gtin);
                        $hp->start_date = $startDate;
                        $hp->end_date = $endDate;
                        $hp->save();
                    }
                }
                DB::commit();
                return Response::json(['success'=> 'Cập nhật thành công'], 200);
            } catch (\Exception $ex) {
                DB::rollBack();
                return Response::json(['error' => 'hệ thống có lỗi! Vui lòng thử lại sau'], 504);
            }

        }

        return Response::json(['error' => 'Doanh nghiệp hết hạn!! hoặc chưa đủ thông tin'], 404); // Status code here
    }

    public function listRelateProductPp(Request $request,$gtin){
        if (auth()->user()->cannot('view-relate-product')) {
            abort(403);
        }
        $business = Auth::user();
        $product = SProduct::where('gtin_code', $gtin)->first();
        if (empty($product)) {
            return redirect()->back()->with('error', 'Không tồn tại sản phẩm');
        }

        $pD2 = ProductDistributor::where('business_id', $business->id)->where('product_id', $product->id)->first();
        if (empty($pD2) || $pD2->is_quota != 1) {
            return redirect()->back()->with('error', 'Sản phẩm chưa được đăng kí hoặc không có quyền xem');
        }
        if (empty($pD2) || $pD2->is_first != 1) {
            return redirect()->back()->with('error', 'Sản phẩm chưa được đăng kí hoặc có quyền sửa');
        }

        $products = ProductDistributor::where('business_id', $business->id)->where('is_first', 1);

        if ($request->input('search')) {
            $search = $request->input('search');
            $searchProduct = SProduct::where('product_name', 'like', '%' .$search . '%')
                ->orWhere('gtin_code', 'like', '%' .$search . '%')->get()->lists('id');
            $products = $products->whereIn('product_id',$searchProduct);
        }

        $products = $products->where('product_id', '!=', $product->id);
        $hook = Hook::where('name', 'like', '%' . $gtin)->first();
        $list_gtin = [];
        $hookId = null;
        if (!empty($hook)) {
            $hookId = $hook->id;
            if ($hook->type == 0) {
                $hook_product = HookProduct::where('hook_id', $hook->id)->orderBy('id', 'asc')->get();
                $list_gtin = clone $hook_product;
                $list_gtin = $list_gtin->lists('product_id')->toArray();
                $list_gtin = SProduct::whereIn('gtin_code',$list_gtin)->get()->lists('id')->toArray();
            }
        }
        $products = $products->whereNotIn('product_id',$list_gtin)->paginate(50);
        return view('business.relate_product.listRelateProductPp', compact('products', 'hook_product', 'hookId', 'gtin'));
    }
}
