<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\Business;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Icheck\Product\Category;

use App\Models\Icheck\Product\Product;
use App\Models\Enterprise\ProductDistributor;
use App\Models\Enterprise\ProductDistributorTemp;
use GuzzleHttp\Exception\RequestException;

use Auth;
use DB;
use App\Models\Icheck\Product\Contribute;
use App\Models\Collaborator\ContributeProduct;
use App\Models\Mongo\Product\PProduct;
use App\Jobs\AddListProductDistributor;
use App\Models\Icheck\Product\AttrDynamic;
use App\Models\Icheck\Product\AttrValue;
use App\Models\Enterprise\Product as ProductDN;
use App\Models\Enterprise\GLN;
class BusinessDistributorController extends Controller
{
    public function listBusinessDistributor(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $businesses = ProductDistributor::where('status',ProductDistributor::STATUS_PENDING_ACTIVATION)->paginate(10);

        return view('staff.management.business_distributor.list_business_distributor', compact('businesses'));
    }


    public function listEditProductDistributor(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $products = ProductDistributorTemp::where('status',ProductDistributorTemp::STATUS_PENDING_APPROVAL)->paginate(10);
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);

        if($products){
            foreach($products as $product){
                $product->renderProperties='';
                if($product->properties){
                    $cat = null;
                    if(json_decode($product->categories)){
                        if(is_array(json_decode($product->categories,true))){
                            $cat = json_decode($product->categories,true);
                        }
                    }
                    if($cat){
                        $cat = Category::whereIn('id',$cat)->get();
                    }
                    $product->renderProperties = static::renderProperties($cat,$product);
                }
            }
        }
        return view('staff.management.business_distributor.list_edit_product', compact('products','categories'));
    }
    public function approveBusiness($id,Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $pD = ProductDistributor::findOrFail($id);
        $pD->status = ProductDistributor::STATUS_ACTIVATED;
        $pD->save();
        return redirect()->back()->with('success','Approve thành công!');
    }

    public function disapproveBusiness($id,Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $pD = ProductDistributor::findOrFail($id);
        $pD->status = ProductDistributor::STATUS_DEACTIVATED;
        $pD->save();
        return redirect()->back()->with('success','Disapprove thành công!');
    }

    public function approveList(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $selected = $request->input('selected');
        if(!empty($selected)){
            ProductDistributor::whereIn('id',$selected)->update(['status' =>ProductDistributor::STATUS_ACTIVATED]);
            return redirect()->back()->with('success','Approve thành công!');
        }

        return redirect()->back();
    }
    public function disapproveList(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $selected = $request->input('selected');

        if(!empty($selected)){
            ProductDistributor::whereIn('id',$selected)->update(['status' =>ProductDistributor::STATUS_DEACTIVATED]);
            return redirect()->back()->with('success','Disapprove thành công!');
        }
        return redirect()->back();
    }


    public function disapproveEdit($id,Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }

        $pD = ProductDistributorTemp::findOrFail($id);
        $pD->status = ProductDistributorTemp::STATUS_DISAPPROVED;
        if($request->input('reason')){
            $pD->reason = $request->input('reason');
        }else{
            $pD->reason = null;
        }
        $pD->save();
        return redirect()->back()->with('success','Disapprove thành công!');
    }

    public function disapproveListEdit(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $selected = $request->input('selected');

        if(!empty($selected)){
            if($request->input('reason')){
                $reason = $request->input('reason');
            }else{
                $reason = null;
            }
            ProductDistributorTemp::whereIn('id',$selected)->update(['status' =>ProductDistributorTemp::STATUS_DISAPPROVED,'reason' => $reason]);
            return redirect()->back()->with('success','Disapprove thành công!');
        }
        return redirect()->back();
    }

    public function approveEdit($id,Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $pD = ProductDistributorTemp::findOrFail($id);
        $product = Product::where('gtin_code',$pD->gtin_code)->first();

        if($pD->product_name!='') {
            $product->product_name = $pD->product_name;
        }
        if($pD->price > 0) {
            $product->price_default = $pD->price;
        }

        if(empty($pD->price)){
            $product->price_default = null;
        }else{
            $product->price_default = $pD->price;
        }

        if($pD->image !=null) {
            $image_p = json_decode($pD->image,true);

            $product->image_default = $image_p[0];


            $m = PProduct::where('gtin_code', $product->gtin_code)->first();


            if ($m) {

                $images = $m->attachments;
                $m->unset('attachments');
                if($images!=null){
                    foreach ($images as $key => $img) {
                        if (isset($images[$key]['type'])) {
                            if ($images[$key]['type'] == 'image') {
                                unset($images[$key]);
                            }else{
                                $m->push('attachments',$images[$key]);
                            }
                        }

                    }
                }

                foreach ($image_p as $image) {
                    if ($image != $product->image_default) {
                        $m->push('attachments',[
                            'type' => 'image',
                            'link' => $image,
                        ]);
                    }

                }

            }else{

                $pproduct = new PProduct();
                $pproduct->gtin_code = $product->gtin_code;
                $pproduct->internal_code = $product->internal_code;
                $pproduct->save();

                foreach ($image_p as $image) {
                    if ($image != $product->image_default) {
                        $pproduct->push('attachments',[
                            'type' => 'image',
                            'link' => $image,
                        ]);
                    }

                }
            }

        }

        $attrs = array();
        foreach (json_decode($pD->attrs) as $key => $value){
            if($value!=''){
                $attrs[$key] = ['content' => $value
                    , 'content_text' => strip_tags($value),
                ];
             }
        }
        if(count($attrs) > 0) {
            $product->attributes()->syncWithoutDetaching($attrs);
        }

        if($pD->categories){
            if(json_decode($pD->categories,true)){
                $product->categories()->sync(json_decode($pD->categories,true));
            }else{
                $product->categories()->sync([]);
            }

        }
        if(json_decode($pD->properties,true)){
            $count_features = 0;
            $features = [];
            $properties = json_decode($pD->properties,true);
            AttrValue::where('product_id',$product->id)->delete();
            foreach ($properties as $key =>  $property){
                if(count($property) > 3){
                    if($count_features < 10){
                        if(trim($property[0])){
                            $features[] = trim( $property[0]);
                            $count_features++;
                        }
                    }
                    if($count_features < 10){
                        if(trim( $property[1])){
                            $features[] = trim( $property[1]);
                            $count_features++;
                        }
                    }
                    if($count_features < 10){
                        if(trim( $property[2])){
                            $features[] = trim( $property[2]);
                            $count_features++;
                        }
                    }
                }else{
                    foreach ($property as $v){
                        if($count_features < 10){
                            if(trim($v)){
                                $features[] = trim($v);
                                $count_features++;
                            }
                        }
                    }
                }
                $v = implode(',',$property);
                $attr_value = [
                    'product_id' => $product->id,
                    'attribute_id' => $key,
                ];
                if($v){
                    $a = AttrValue::firstOrCreate($attr_value);
                    $a->content = $v;
                    $a->save();
                }

            }
            if($features){
                $f = implode(',',$features);
                $product->features = $f;
            }
        }

        $product->save();

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('PUT', env('DOMAIN_API') . 'products/'. $product->id, [
                'auth' => [env('USER_API'), env('PASS_API')],
                'timeout' => 5
            ]);
            $res = json_decode((string)$res->getBody());

            if ($res->status != 200) return redirect()->back()->with('danger', 'cập nhật thông tin Sản phẩm bi loi khi dong bo redis');
        } catch (RequestException $e) {
//            return $e->getResponse()->getBody();
        }
        if($request->input('reason')){
            $pD->reason = $request->input('reason');
        }else{
            $pD->reason = null;
        }

        $pD->status = ProductDistributorTemp::STATUS_APPROVED;
        $pD->save();


        Contribute::where('gtin_code',$product->gtin_code)->update(['status'=>Contribute::STATUS_DISAPPROVED]);
        ContributeProduct::whereIn('status',[ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->where('gtin',$product->gtin_code)->delete();

        return redirect()->back()->with('success','Approve thành công!');
    }
    public function approveListEdit(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $selected = $request->input('selected');

        if(!empty($selected)){
                foreach ($selected as $key => $id){

                    $pD = ProductDistributorTemp::findOrFail($id);
                    $product = Product::where('gtin_code',$pD->gtin_code)->first();

                    if($pD->product_name!=''){
                        $product->product_name = $pD->product_name;
                    }

                    if(empty($pD->price)){
                        $product->price_default = null;
                    }else{
                        $product->price_default = $pD->price;
                    }

                    if($pD->image !=null){
                        $image_p = json_decode($pD->image,true);

                        $product->image_default = $image_p[0];


                        $m = PProduct::where('gtin_code', $product->gtin_code)->first();


                        if ($m) {
                            $images = $m->attachments;
                            $m->unset('attachments');
                            if($images!=null){
                                foreach ($images as $key => $img) {
                                    if (isset($images[$key]['type'])) {
                                        if ($images[$key]['type'] == 'image') {
                                            unset($images[$key]);
                                        }else{
                                            $m->push('attachments',$images[$key]);
                                        }
                                    }

                                }
                            }

                            foreach ($image_p as $image) {
                                if ($image != $product->image_default) {
                                    $m->push('attachments',[
                                        'type' => 'image',
                                        'link' => $image,
                                    ]);
                                }

                            }
                        }else {
                            $pproduct = new PProduct();
                            $pproduct->gtin_code = $product->gtin_code;
                            $pproduct->internal_code = $product->internal_code;
                            $pproduct->save();
                            foreach ($image_p as $image) {
                                if ($image != $product->image_default) {
                                    $pproduct->push('attachments',[
                                        'type' => 'image',
                                        'link' => $image,
                                    ]);
                                }

                            }
                        }
                    }

                    $attrs = array();
                    if(json_decode($pD->attrs)) {
                        foreach (json_decode($pD->attrs) as $key => $value){
                            if($value!=''){
                                $attrs[$key] = ['content' => $value
                                    , 'content_text' => strip_tags($value),
                                ];
                            }
                        }
                    }

                    if(count($attrs) > 0) {
                        $product->attributes()->syncWithoutDetaching($attrs);
                    }
                    if($pD->categories) {
                        if(json_decode($pD->categories,true)){
                            $product->categories()->sync(json_decode($pD->categories,true));
                        }else{
                            $product->categories()->sync([]);
                        }

                    }
                    if(json_decode($pD->properties,true)){
                        $count_features = 0;
                        $features = [];
                        $properties = json_decode($pD->properties,true);
                        foreach ($properties as $key =>  $property){
                            if(count($property) > 3){
                                if($count_features < 10){
                                    if(trim($property[0])){
                                        $features[] = trim( $property[0]);
                                        $count_features++;
                                    }
                                }
                                if($count_features < 10){
                                    if(trim( $property[1])){
                                        $features[] = trim( $property[1]);
                                        $count_features++;
                                    }
                                }
                                if($count_features < 10){
                                    if(trim( $property[2])){
                                        $features[] = trim( $property[2]);
                                        $count_features++;
                                    }
                                }
                            }else{
                                foreach ($property as $v){
                                    if($count_features < 10){
                                        if(trim($v)){
                                            $features[] = trim($v);
                                            $count_features++;
                                        }
                                    }
                                }
                            }
                            $v = implode(',',$property);
                            $attr_value = [
                                'product_id' => $product->id,
                                'attribute_id' => $key,
                            ];
                            if($v){
                                $a = AttrValue::firstOrCreate($attr_value);
                                $a->content = $v;
                                $a->save();
                            }

                        }
                        if($features){
                            $f = implode(',',$features);
                            $product->features = $f;
                        }
                    }
                    $pD->reason = null;
                    $product->save();
                    $client = new \GuzzleHttp\Client();
                    try {
                        $res = $client->request('PUT', env('DOMAIN_API') . 'products/'. $product->id, [
                            'auth' => [env('USER_API'), env('PASS_API')],
                            'timeout' => 5
                        ]);
                        $res = json_decode((string)$res->getBody());

                        if ($res->status != 200) return redirect()->back()->with('danger', 'cập nhật thông tin Sản phẩm bi loi khi dong bo redis');
                    } catch (RequestException $e) {
                        return $e->getResponse()->getBody();
                    }

                    $pD->status = ProductDistributorTemp::STATUS_APPROVED;
                    $pD->save();

                    Contribute::where('gtin_code',$product->gtin_code)->update(['status'=>Contribute::STATUS_DISAPPROVED]);
                    ContributeProduct::whereIn('status',[ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->where('gtin',$product->gtin_code)->delete();
                }

            return redirect()->back()->with('success','Approve thành công!');
        }

        return redirect()->back();
    }

    public static function r($data, $parent = 0, $level = 0)
    {
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

    public function inline($id,Request $request){

        $product = ProductDistributorTemp::findOrFail($id);

        if($request->input('product_name')){
            $product->product_name = $request->input('product_name');
            $product->save();
        }
        if($request->input('price')!=null){
            $product->price = $request->input('price');
            $product->save();
        }
        if($request->input('attr-1')!=null){
            $attrs = json_decode($product->attrs,True);
            $attrs[1] = $request->input('attr-1');
            $product->attrs = json_encode($attrs);
            $product->save();
        }
        if($request->input('attr-2')!=null){
            $attrs = json_decode($product->attrs,True);
            $attrs[2] = $request->input('attr-2');
            $product->attrs = json_encode($attrs);
            $product->save();
        }
        if($request->input('attr-4')!=null){
            $attrs = json_decode($product->attrs,True);
            $attrs[4] = $request->input('attr-4');
            $product->attrs = json_encode($attrs);
            $product->save();
        }
        if($request->input('categories')!=null){
            $categories = json_decode($request->input('categories'));

            if($categories){

                $result  = [];

                foreach ($categories as $id){
                    $category = Category::find($id);
                    $r = $this->getParent($category,[]);

                    $r[] = intval($id);
                    $result = array_unique( array_merge( $result , $r ) );
                };
                $product->categories = json_encode($result);
            }else{
                $product->categories = json_encode($categories);
            }
            $product->save();
        }
        if($request->input('images')){
            $images = $request->input('images');
            if($images=='del'){
                $images = null;
            }
            $product->image =$images;
            $product->save();

        }
        return 'oke';
    }


    public function index(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $count_product= 0;
        $businesses = Business::all();
        $products = null;
        if($request->input('business_id')){
            $business_id = $request->input('business_id');
            $pT = ProductDistributor::where('business_id',$business_id);

            $products = Product::latest('createdAt');

            if($request->input('search')){

                $products = $products->where('product_name','like','%'.$request->input('search').'%')->orWhere('gtin_code',$request->input('search'));

            }

            if($request->input('is_first')!=null){
                $is_first = $request->input('is_first');
                if($is_first!=2){

                    $pT = $pT->where('is_first',$is_first);

                }

                $pT = $pT->pluck('product_id');
                $products = $products->whereIn('id',$pT);

            }
            $count_product = clone $products;
            $count_product = $count_product->count();
            $products = $products->paginate(10);

        }
        $total_count = ProductDistributor::groupBy('product_id')->get()->count();
        return view('staff.management.business_distributor.index',compact('businesses','products','count_product','total_count'));
    }

    public function changeEdit(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $data = $request->all();
        $error = '';
        $business_id = $data['business_id'];
        $change_id = $data['id'];
        $product_id  = $data['product_id'];



        if(!empty($product_id)) {
            $product_id = explode(',',$product_id);

            foreach ($product_id as $key => $value) {

                $change_product = ProductDistributor::where('business_id', $change_id)->where('product_id', $value)->first();
                $pD = ProductDistributor::where('business_id', $business_id)->where('product_id', $value)->first();

                if (!empty($change_product) && $pD->is_first== 1) {
                    $pD->is_first = 0;
                    $pD->save();
                    $change_product->is_first = 1;
                    $change_product->save();
                } else {
                    $error = $error . '</br>' . $pD->product->product_name . '(' . $pD->product->gtin_code . ')';
                }
            }
            if ($error == '') {
                return redirect()->back()->with('success', 'Chuyển quyền thành công');
            }
            return redirect()->back()->with('error', 'Lỗi với sản phẩm : ' . $error);
        }
        return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm  ');
    }

    public function addProductDistributor(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $products = null;
        $businesses = Business::all();
        if ($request->input('q')) {
            $products = Product::where(function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('gtin_code','like', '%' . $request->input('q') . '%');
            });
            $products = $products->paginate(10);

        }
        return view('staff.management.business_distributor.add_product_distributor',compact('products','businesses'));
    }
    public function storeProductDistributor(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $data = $request->all();
        $id = $data['id'];
        $product_id = $data['product_id'];
        if(!empty($product_id)){
            $product_ids = explode(',',$product_id);
            DB::beginTransaction();

            foreach ($product_ids as $value){
                try{
                    $is_first = 0;
                    $count = ProductDistributor::where('product_id',$value)->where('is_first',1)->count();

                    if($count == 0){
                        $is_first = 1;
                    }
                    $pD = ProductDistributor::firstOrCreate(['business_id'=>$id,'product_id' => $value]);

                    if($pD->is_first!=1){
                        $pD->is_first = $is_first;
                    }

                    $pD->status=ProductDistributor::STATUS_ACTIVATED;
                    $pD->save();
                    DB::commit();
                }catch(\Exception $ex){
                    DB::rollBack();
                }

            }
            return redirect()->back()->with('success', 'Đăng kí phân phối thành công');
        }else{
            return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm muốn phân phối');
        }
    }
    public function deleteDistributor($id,Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $data = $request->all();

        $business_id = $data['business_id'];
        $product_id = $data['product_id'];
        $error = '';
        if(!empty($product_id)) {
            $product_id = explode(',',$product_id);

            foreach ($product_id as $key => $value) {

                $pD = ProductDistributor::where('business_id', $business_id)->where('product_id', $value)->first();

                if ($pD->is_first== 0) {
                    $pD->delete();
                } else {
                    $error = $error . '</br>'.$pD->product->product_name . '(' . $pD->product->gtin_code . ') vì sản phẩm đang có quyền sửa ' ;
                }
            }
            if ($error == '') {
                return redirect()->back()->with('success', 'Xóa thành công');
            }
            return redirect()->back()->with('error', 'Lỗi với sản phẩm : ' . $error);
        }
        return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm  ');
    }

    public function delete(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $data = $request->all();
        $business_id = $data['business_id'];
        $product_id = $data['product_id'];
        $error = '';
        if(!empty($product_id)) {
            $product_id = explode(',',$product_id);

            foreach ($product_id as $key => $value) {

                $pD = ProductDistributor::where('business_id', $business_id)->where('product_id', $value)->first();

                if ($pD->is_first== 1) {
                    $pD->is_first = 0;
                    $pD->save();
                } else {
                    $error = $error . '</br>'.$pD->product->product_name . '(' . $pD->product->gtin_code . ') vì sản phẩm không có quyền sửa ' ;
                }
            }
            if ($error == '') {
                return redirect()->back()->with('success', 'Xóa quyền thành công');
            }
            return redirect()->back()->with('error', 'Lỗi với sản phẩm : ' . $error);
        }
        return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm  ');
    }

    public function listProductBusiness(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        $products = null;
        if($request->input('search')){

            $products = Product::where(function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('gtin_code','like', '%' . $request->input('search') . '%');
            });
            $products = $products->paginate(10);
        }

        return view('staff.management.business_distributor.list_product_business',compact('products'));
    }
    public function getProductBusiness(Request $request , $id){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }

        $productDistributors =  ProductDistributor::where('product_id',$id);
        if($request->input('is_first')!=null){

            $is_first = $request->input('is_first');
            if($is_first != 2){
                $productDistributors = $productDistributors->where('is_first',$is_first);
            }

        }
        $productDistributors=$productDistributors->paginate(10);
        return view('staff.management.business_distributor.get_info_product_distributor',compact('productDistributors'));
    }
    public function changePermissionEdit(Request $request,$id){
        $pD = ProductDistributor::find($id);
        if($pD){
            ProductDistributor::where('product_id',$pD->product_id)->where('id','!=',$id)->update(['is_first' => 0]);
            $pD->is_first = 1;
            $pD->save();
            return redirect()->back()->with('success','Chuyển quyền thành công');
        }
        return redirect()->back()->with('error','Không tồn tại phân phối sản phẩm');
    }

    public function addList(Request $request){
        if (auth()->guard('staff')->user()->cannot('business-product-distributor')) {
            abort(403);
        }
        if($request->input('gtin')){
            $id = $request->input('id');
            $gtins = $request->input('gtin');
            $this->dispatch(new AddListProductDistributor($gtins,$id,auth('staff')->user()->email));

            return redirect()->back()
                ->with('success', 'List gtin_code đã được thêm vào queue   ');
        }
        return redirect()->back()
            ->with('success', 'Vui lòng điền sản phẩm ');
    }
    // change categories list
    public static function getParent($category,$parent){
        $c_parent = Category::find($category->parent_id);
        if($c_parent){
            if($c_parent->parent_id == 12){
                $parent[] = $c_parent->id;
                return $parent;
            }
            $parent[] = $c_parent->id;
            return static::getParent($c_parent,$parent);
        }
        return [];

    }
    public static function getAllCategories($categories, $level = 0) {
        $allCategories = array();

        foreach ($categories as $category) {
            $subArr = array();
            $subArr['name'] = $category->name;
            $subArr['id'] = $category->id;
            $subArr['level'] = $level;
            $subArr['parent_id'] = $category->parent_id;
            $subArr['attributes'] = $category->attributes;


            $subCategories = Category::where('parent_id', '=', $category->id)->get();

            if (!$subCategories->isEmpty()) {
                $result = static::getAllCategories($subCategories,$level+1);

                $subArr['sub'] = $result;
            }

            $allCategories[] = $subArr;
        }

        return $allCategories;
    }


    public function addQuota(Request $request){
        $this->validate($request,[
           'gtins' => 'required'
        ],[
            'gtins.required' => 'Vui lòng nhập gtins'
        ]);
        $id = $request->input('id');
        $business = Business::find($id);
        $gln = $business->gln()->where('status',GLN::STATUS_APPROVED)->get()->lists('id')->toArray();
        $gtins = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtins'));
        $role = $business->roles()->first();
        if($role){
            if($role->quota){
                $quota = $role->quota;
                $countSx = ProductDN::whereIn('gln_id', $gln)->where('is_quota',1)->count();
                $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
                $exist =  $quota-$countPP-$countSx;
                if(count($gtins) > $exist){
                    return redirect()->back()->with('error','Lượng quota còn lại không đủ.Chỉ còn '.$exist);
                }
                if($exist > 0 ){
                    $products = Product::whereIn('gtin_code',$gtins)->get()->lists('id')->toArray();
                    ProductDistributor::where('business_id',$business->id)->whereIn('product_id',$products)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',0)->update(['is_quota' => 1]);
                    return redirect()->route('Staff::Management::businessDistributor@index')->with('success','Set sản phẩm phân phối trong quota thành công');
                }
            }
        }else{
            return redirect()->back()->with('error','Doanh nghiệp chưa được set role');
        }
    }
    public function removeQuota(Request $request){
        $this->validate($request,[
            'gtins' => 'required'
        ],[
            'gtins.required' => 'Vui lòng nhập gtins'
        ]);

        $id = $request->input('id');
        $business = Business::find($id);
        $gtins = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtins'));

                    $products = Product::whereIn('gtin_code',$gtins)->get()->lists('id')->toArray();
                    ProductDistributor::where('business_id',$business->id)->whereIn('product_id',$products)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->update(['is_quota' => 0]);
                    return redirect()->route('Staff::Management::businessDistributor@index')->with('success','Remove sản phẩm phân phối trong quota thành công');


    }

    public static function renderProperties($categories, $product)  {
        $attr_value = json_decode($product->properties,true);
        $result = '';
        $att_array = [];
        if (empty($attr_value)) {
            return '';
        }

        if ($categories) {
            foreach ($categories as $category) {
                $attributes = $category->attributes;
                if ($attributes) {
                    $attributes = explode(',', $attributes);
                    foreach ($attributes as $at) {
                        if (isset($att_array[$at])) {
                            $att_array[$at] = intval($att_array[$at]) + 1;
                        } else {
                            $att_array[$at] = 1;
                        }
                    }
                }

            }
        }

        if ($attr_value) {
            foreach ($attr_value as $key => $value) {
                $property = AttrDynamic::find($key);

                if ($property) {
                    $count = 1;
                    if (isset($attr_array[$property->id])) {
                        $count = $attr_array[$property->id];
                    }
                    $result .= static::templateProperties($property, $count, $value,$product->id);
                }
            }
        }
        return $result;
    }

    private static function templateProperties($attr, $count = 1, $properties,$productId){
        $string = null;
        if ($attr->enum) {
            if (trim($attr->type) == 'single') {
                $value = $attr->enum;
                $value = explode(',', $value);
                $s = '';
                foreach ($value as $v) {

                    if (in_array($v, $properties)) {
                        $s .= '<option value="' . $v . '" selected>' . $v . '</option>';

                    } else {
                        $s .= '<option value="' . $v . '">' . $v . '</option>';
                    }

                }
                $string = '<div class="row" id="'.$productId.$attr->id.'" data-count="' . $count . '" data-id="' . $attr->id . '"><div class="col-md-5"><label for="country" class="control-label text-semibold">' . $attr->title . '</label></div><div class="col-md-7">
                        <select  class="select-border-color border-warning js-attr properties-product">
                                                   ' . $s . '
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
            if (trim($attr->type) == 'multiple') {
                $value = $attr->enum;
                $value = explode(',', $value);
                $s = '';
                foreach ($value as $v) {

                    if (in_array($v, $properties)) {
                        $s .= '<option value="' . $v . '" selected>' . $v . '</option>';
                    } else {
                        $s .= '<option value="' . $v . '">' . $v . '</option>';
                    }
                }
                $string = '<div class="row" id="'.$productId.$attr->id.'" data-count="' . $count . '" data-id="' . $attr->id . '"><div class="col-md-5"><label for="country" class="control-label text-semibold">' . $attr->title . '</label></div><div class="col-md-7">
                        <select " class="select-border-color border-warning js-attr  properties-product"  multiple="multiple">
                                                   ' . $s . '
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        } else {
            $t = '';

            if (isset($properties)) {
                $properties = implode(',', $properties);
                $t = 'value="' . $properties . '"';
            }
            $string = '<div class="row" id="'.$productId.$attr->id.'" data-count="' . $count . '" data-id="' . $attr->id . '"><div class="col-md-6"><label for="country" class="control-label text-semibold">' . $attr->title . '</label></div><div class="col-md-6">
                        <input maxlength="25"  ' . $t . ' type="text" class="form-control  properties-product">
                        
                                                  
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }
}
