<?php

namespace App\Http\Controllers\Staff\Management;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Models\Enterprise\User;
use App\Http\Controllers\Controller;
use App\Models\Icheck\User\Account;
//use App\Models\Mongo\Product\PProduct;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use App\Models\Icheck\Product\Product;
use App\Models\Icheck\Product\CategoryProduct;
use Illuminate\Support\Facades\Session;
use App\Models\Icheck\Product\Category;
use App\Models\Icheck\Product\Vendor;

class RelateProductController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('relate_product')) {
            abort(403);
        }
        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        $product = Product::where('gtin_code', $request->input('gtin_code'))->first();
        $input_gtin = $request->input('gtin_code');
        if (empty($product)) {
            Session::flash('danger', 'Mã gtin không đúng! Vui lòng nhập lại');
            return view('staff.management.relate_product.index');
        }
        if ($request->input('type') != null) {
            $type = $request->input('type');
            if ($type == 1) {
                //theo san pham
                if ($request->input('search')) {


                    $products = Product::where(function ($query) use ($request) {
                        $query->where('product_name', 'like', '%' . $request->input('search') . '%')
                            ->orWhere('gtin_code', 'like', '%' . $request->input('search') . '%');
                    });
                    $products = $products->orderBy('id','asc')->paginate(10);

                } else {
                    $products = Product::where('gtin_code', '!=',$input_gtin)->orderBy('id','asc')->paginate(15);
                }


            } elseif ($type == 2) {
                // cung category
                $category = CategoryProduct::where('product_id', $product->id)->get()->lists('category_id');
                $product_categories = CategoryProduct::whereIn('category_id', $category)->distinct('product_id')->with('product')->paginate(15);

            } elseif ($type == 3) {
                // cung vendor
                $product_vendors = Product::where('gtin_code', '<>', $input_gtin)->where('vendor_id', $product->vendor_id)->paginate(15);
            } elseif ($type == 4) {
                // nhieu category
                $categories = Category::all()->groupBy('parent_id');
                $categories = $this->r($categories,0);


            } elseif ($type == 5) {
                $vendors = Vendor::paginate(15);
            }
        }

        $hook = Hook::where('name','like','%'.$input_gtin)->first();

        if(!empty($hook)){
            $hookId = $hook->id;
            if($hook->type==0){

                $hook_product = HookProduct::where('hook_id',$hook->id)->orderBy('id','asc')->get();

            }elseif($hook->type==1){
                $iql = $hook->iql;
                $len = strlen($iql) -30;
                $hCategory = substr($iql,27,$len);
                $hCategory = explode(',',$hCategory);
                $hCategories = Category::whereIn('id',$hCategory)->get();
            }elseif($hook->type==2){
                $iql = $hook->iql;
                $len = strlen($iql) -28;
                $hVendor = substr($iql,25,$len);
                $hVendor = explode(',',$hVendor);
                $hVendors = Vendor::whereIn('id',$hVendor)->get();

            }
        }


        return view('staff.management.relate_product.index', compact('products', 'product_categories', 'categories', 'product_vendors', 'categories', 'vendors','hook_product','hCategories','hVendors','hookId'));
    }

    public function addProduct(Request $request)
    {

        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        if ($request->input('selected')) {
            if (empty($request->input('start-date')) | empty($request->input('end-date'))) {
                return redirect()->back()->with('danger', 'Vui lòng chọn ngày start và end');
            }
            $start_date = $request->input('start-date');
            $end_date = $request->input('end-date');
            $selected = $request->input('selected');
            $gtin_code = $request->input('gtin_code');

            $list_gtin = Product::whereIn('id',$selected)->get()->lists('gtin_code')->toArray();


            $name = 'prod:' . $gtin_code;
            $hook = Hook::firstOrCreate(['name' => $name]);
            $hook->iql = null;
            $hook->type=0;
            $hook->save();
            HookProduct::where('hook_id',$hook->id)->delete();
            foreach ($list_gtin as $gtin){
                $hook_product =new HookProduct();
                $hook_product->hook_id = $hook->id;
                $hook_product->product_id = $gtin;
                $hook_product->start_date = Carbon::createFromTimestamp(strtotime($start_date));
                $hook_product->end_date = Carbon::createFromTimestamp(strtotime($end_date));
                $hook_product->save();

            }


            return redirect()->route('Staff::Management::relateProduct@index', ['gtin_code' => $gtin_code])->with('success','Set sản phẩm liên quan thành công');
        }
        return redirect()->back()->with('danger', 'Vui lòng chọn sản phẩm')->withInput();
    }

    public function addProductCat(Request $request){
        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        if ($request->input('selected')) {
            if (empty($request->input('start-date')) | empty($request->input('end-date'))) {
                return redirect()->back()->with('danger', 'Vui lòng chọn ngày start và end');
            }
            $start_date = $request->input('start-date');
            $end_date = $request->input('end-date');
            $selected = $request->input('selected');
            $gtin_code = $request->input('gtin_code');

            $list_gtin = Product::whereIn('id',$selected)->get()->lists('gtin_code')->toArray();
            $list_gtins = implode(',', $list_gtin);


            $name = 'prod:' . $gtin_code;
            $hook = Hook::firstOrCreate(['name' => $name]);
            $hook->iql = null;
            $hook->type=0;
            $hook->save();
            HookProduct::where('hook_id',$hook->id)->delete();
            foreach ($list_gtin as $gtin){
                $hook_product =new HookProduct();
                $hook_product->hook_id = $hook->id;
                $hook_product->product_id = $gtin;
                $hook_product->start_date = Carbon::createFromTimestamp(strtotime($start_date));
                $hook_product->end_date = Carbon::createFromTimestamp(strtotime($end_date));
                $hook_product->save();

            }

            return redirect()->route('Staff::Management::relateProduct@index', ['gtin_code' => $gtin_code])->with('success','Set sản phẩm liên quan thành công');
        }
        return redirect()->back()->with('danger', 'Vui lòng chọn sản phẩm');
    }
    public function addProductCatAll(Request $request){
        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        if (empty($request->input('start-date')) | empty($request->input('end-date'))) {
            return redirect()->back()->with('danger', 'Vui lòng chọn ngày start và end');
        }
        $start_date = $request->input('start-date');
        $end_date = $request->input('end-date');
        $gtin_code = $request->input('gtin_code');
        $product = Product::where('gtin_code',$gtin_code)->first();
        $category = CategoryProduct::where('product_id', $product->id)->get()->lists('category_id')->toArray();

        if(count($category) < 1){
            return redirect()->back()->with('danger', 'Sản phẩm không có danh mục');
        }
        $category_id = implode(',',$category);

        $name = 'prod:' . $gtin_code;
        $iql = 'Product.find({category_id:[' . $category_id . ']})';

        $hook = Hook::firstOrCreate(['name' => $name]);
        $hook->iql = $iql;
        $hook->type = 1;
        $hook->save();
        HookProduct::where('hook_id',$hook->id)->delete();
//        $hook_product = HookProduct::firstOrCreate(['hook_id'=> $hook->id]);
//
//        $hook_product->product_id = $gtin_code;
//        $hook_product->start_date = Carbon::createFromTimestamp(strtotime($start_date));
//        $hook_product->end_date = Carbon::createFromTimestamp(strtotime($end_date));
//        $hook_product->save();
        return redirect()->route('Staff::Management::relateProduct@index', ['gtin_code' => $gtin_code])->with('success','Set sản phẩm liên quan thành công');

    }

    public function addProductVendor(Request $request){
        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        if ($request->input('selected')) {
            $start_date = $request->input('start-date');
            $end_date = $request->input('end-date');
            $selected = $request->input('selected');
            $gtin_code = $request->input('gtin_code');

            $list_gtin = Product::whereIn('id',$selected)->get()->lists('gtin_code')->toArray();
            $list_gtins = implode(',', $list_gtin);


            $name = 'prod:' . $gtin_code;
            $hook = Hook::firstOrCreate(['name' => $name]);
            $hook->iql = null;
            $hook->type=0;
            $hook->save();
            HookProduct::where('hook_id',$hook->id)->delete();
            foreach ($list_gtin as $gtin){
                $hook_product =new HookProduct();
                $hook_product->hook_id = $hook->id;
                $hook_product->product_id = $gtin;
                $hook_product->start_date = Carbon::createFromTimestamp(strtotime($start_date));
                $hook_product->end_date = Carbon::createFromTimestamp(strtotime($end_date));
                $hook_product->save();

            }


            return redirect()->route('Staff::Management::relateProduct@index', ['gtin_code' => $gtin_code])->with('success','Set sản phẩm liên quan thành công');
        }
        return redirect()->back()->with('danger', 'Vui lòng chọn sản phẩm');
    }

    public function addProductVendorAll(Request $request){
        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        if (empty($request->input('start-date')) | empty($request->input('end-date'))) {
            return redirect()->back()->with('danger', 'Vui lòng chọn ngày start và end');
        }
        $start_date = $request->input('start-date');
        $end_date = $request->input('end-date');
        $gtin_code = $request->input('gtin_code');
        $product = Product::where('gtin_code',$gtin_code)->first();


        if(empty($product->vendor_id)){
            return redirect()->back()->with('danger', 'Sản phẩm không có vendor');
        }


        $name = 'prod:' . $gtin_code;
        $iql = 'Product.find({vendor_id:[' . $product->vendor_id . ']})';

        $hook = Hook::firstOrCreate(['name' => $name]);
        $hook->iql = $iql;
        $hook->type = 2;
        $hook->save();

        HookProduct::where('hook_id',$hook->id)->delete();
        return redirect()->route('Staff::Management::relateProduct@index', ['gtin_code' => $gtin_code])->with('success','Set sản phẩm liên quan thành công');

    }

    public function addCat(Request $request){

        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        if ($request->input('selected')) {
            if (empty($request->input('start-date')) | empty($request->input('end-date'))) {
                return redirect()->back()->with('danger', 'Vui lòng chọn ngày start và end');
            }
            $start_date = $request->input('start-date');
            $end_date = $request->input('end-date');
            $selected = $request->input('selected');
            $gtin_code = $request->input('gtin_code');

            $ids = implode(',', $selected);
            $iql = 'Product.find({category_id:[' . $ids . ']})';

            $name = 'prod:' . $gtin_code;

            $hook = Hook::firstOrCreate(['name' => $name]);
            $hook->iql = $iql;
            $hook->type=1;
            $hook->save();


            HookProduct::where('hook_id',$hook->id)->delete();

            return redirect()->route('Staff::Management::relateProduct@index', ['gtin_code' => $gtin_code])->with('success','Set sản phẩm liên quan thành công');
        }
        return redirect()->back()->with('danger', 'Vui lòng chọn sản phẩm');
    }

    public function addVendor(Request $request){

        if ($request->input('gtin_code') == null) {
            Session::flash('danger', 'Vui lòng nhập mã gtin code');
            return view('staff.management.relate_product.index');
        }
        if ($request->input('selected')) {
            if (empty($request->input('start-date')) | empty($request->input('end-date'))) {
                return redirect()->back()->with('danger', 'Vui lòng chọn ngày start và end');
            }
            $start_date = $request->input('start-date');
            $end_date = $request->input('end-date');
            $selected = $request->input('selected');
            $gtin_code = $request->input('gtin_code');

            $ids = implode(',', $selected);
            $iql = 'Product.find({vendor_id:[' . $ids . ']})';

            $name = 'prod:' . $gtin_code;

            $hook = Hook::firstOrCreate(['name' => $name]);
            $hook->iql = $iql;
            $hook->type = 2;
            $hook->save();
            HookProduct::where('hook_id',$hook->id)->delete();
//            $hook_product = HookProduct::firstOrCreate(['hook_id'=> $hook->id]);
//
//            $hook_product->product_id = $gtin_code;
//            $hook_product->start_date = Carbon::createFromTimestamp(strtotime($start_date));
//            $hook_product->end_date = Carbon::createFromTimestamp(strtotime($end_date));
//            $hook_product->save();


            return redirect()->route('Staff::Management::relateProduct@index', ['gtin_code' => $gtin_code])->with('success','Set sản phẩm liên quan thành công');
        }
        return redirect()->back()->with('danger', 'Vui lòng chọn sản phẩm');
    }

    public function order(Request $request){
        $first = [];
        $second = [];
        $gtin = [];
        if($request->input('my_teams')){
            $first = $request->input('my_teams');
            foreach ($first as $p){
                $gtin[] = $p;
            }
        }

        if($request->input('other_teams')){
            $second = $request->input('other_teams');
            foreach ($second as $p){
                $gtin[] = $p;
            }
        }

        $id = $request->input('id');

        $hook_products = HookProduct::where('hook_id',$id);
        $date = clone $hook_products;
        $date = $date->first();
        $start_date = $date->start_date;
        $end_date = $date->end_date;

        $hook_products->delete();

        foreach($gtin as $product_id){
            $hp = new HookProduct();
            $hp->hook_id = $id;
            $hp->product_id = $product_id;
            $hp->start_date = $start_date;
            $hp->end_date = $end_date;
            $hp->save();
        }

        return redirect()->back()->with('success', 'Sắp xếp thành công');
    }
    public static function r($data, $parent = 0, $level = 0) {
        $list = [];
        if (isset($data[$parent])) {
            foreach ($data[$parent] as $cat) {
                $cat->level = $level;
                $list[] = $cat;

                foreach (static::r($data, $cat['id'], $level + 1) as $subCat) {
                    $list[] = $subCat;
                }
            }
        }

        return $list;
    }
    public function deleteRelateProduct(Request $request,$id){
        $selected = $request->input('selected');
        if($selected){
            HookProduct::where('hook_id',$id)->whereIn('product_id',$selected)->delete();
            return redirect()->back()->with('success','Xóa thành công ...');
        }else{
            return redirect()->back()->with('error','Vui lòng chọn gtin để xóa...');
        }
    }
    public function deleteRelateAll(Request $request,$id){

        $hook = Hook::find($id);
        $hook->delete();
        return redirect()->back()->with('success','Xóa thành công ...');
    }
}
