<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\Business;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\Product;
use App\Models\Enterprise\ProductCategory;
use App\Models\Enterprise\MICheckReport;
use App\Models\Icheck\Product\Category;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Icheck\Product\Message;
use Carbon\Carbon;
use App\Models\Icheck\Product\Product as Product2;
use App\Models\Icheck\Product\DistributorProduct;
use App\Models\Icheck\Product\Distributor;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\Currency;
use App\Models\Icheck\Product\ProductInfo;
use App\Models\Icheck\Product\Product as SProduct;
use App\Models\Collaborator\ContributeProduct;
use App\Models\Icheck\Product\Contribute;
use App\Models\Mongo\Product\PProduct;
use GuzzleHttp\Exception\RequestException;
use Auth;
use App\Models\Icheck\Product\ProductMessage;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use Illuminate\Support\Str;
use App\Models\Icheck\Product\AttrDynamic;
use App\Models\Icheck\Product\AttrValue;
use App\Models\Enterprise\ProductDistributor;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-product')) {
            abort(403);
        }
        $products = Product::where('status', Product::STATUS_PENDING_APPROVAL);

        if ($request->has('gln')) {
            $gln = GLN::where('gln', $request->input('gln'))->first();

            if ($gln) {
                $products = $products->where('gln_id', $gln->id);
            }
        }

        $products = $products->orderBy('updated_at', 'desc')->paginate(10);

        foreach ($products as $product) {
            $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();
            $categories = Category::whereIn('id', $selectedCategories)->get();
            $catPro = clone $categories;
            $product->renderProperties='';

            if(json_decode($product->properties,true)){
                $product->renderProperties = static::renderProperties($catPro,$product);
            }

            $categories = $categories->lists('id')->toArray();

            $cat[$product->id] = $categories;
        }
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);

        return view('staff.management.product.index', compact('products', 'cat','categories'));
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

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-product')) {
            abort(403);
        }

        $messages = Message::all();
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);
        $attributes = ProductAttr::all();
        $gln = GLN::where('status', GLN::STATUS_APPROVED)->get();
        $selectedCategories = [];
        return view('staff.management.product.form', compact('categories','selectedCategories', 'attributes', 'gln', 'messages'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-product')) {
            abort(403);
        }

//        $this->validate($request, [
//            'images' => 'required',
//        ]);

        $data = $request->all();
        if($product = Product2::where('gtin_code',$data['barcode'])->first()){
            return redirect()->back()->with('error','Đã tồn tại barcode trên hệ thống');
        }
//        $client = new \GuzzleHttp\Client();
//
//        try {
//            $res = $client->request(
//                'POST',
//                'http://upload.icheck.vn/v1/images?uploadType=simple',
//                [
//                    'body' => file_get_contents($request->file('image')),
//                ]
//            );
//            $res = json_decode((string) $res->getBody());
//        } catch (RequestException $e) {
//            return $e->getResponse()->getBody();
//        }
        if(isset($data['images'])){
            $data['image'] = json_encode($data['images']);
        }else{
            $data['image'] = null;
        }



        $data['status'] = Product::STATUS_PENDING_APPROVAL;
//        $data['gln_id'] = 0;
//        $data['icheck_id'] = 1;
//        $data['warning'] = 0;

        $data['attrs'] = [];
        if($data['highlight_content']){
            foreach ($data['highlight_content'] as $key => $content){
                if($content){
                    if($data['highlight_id'][$key]){
                        $data['attrs'][$data['highlight_id'][$key]] = $content;
                    }

                }
            }
        }
        $product = Product::create($data);
        $product->gln()->associate($request->input('gln_id'));

        if(isset($data['categories'])){

            $result  = [];

            foreach ($data['categories'] as $id){
                $category = Category::find($id);
                $r = $this->getParent($category,[]);

                $r[] = intval($id);
                $result = array_unique( array_merge( $result , $r ) );
            }
            $data['categories'] = $result;

        }else{
            $data['categories'] = [];
        }
        if($data['categories']){
            foreach ($data['categories'] as $cat) {
                ProductCategory::create(['product_id' => $product->id, 'category_id' => $cat]);
            }
        }

        if (isset($data['warning_id'])) {
            ProductMessage::create([
                'gtin_code' => $product->gtin_code,
                //'gln_code' => @$product->gln->gln,
                'message_id' => $data['warning_id'],
            ]);
        } else {
            ProductMessage::where('gtin_code', $product->gtin_code)->delete();
        }


        $product->save();

        return redirect()->route('Staff::Management::product@index')
            ->with('success', 'Đã thêm Sản phẩm');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $messages = Message::all();
        $product = Product::findOrFail($id);
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);
        $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();
        $attributes = ProductAttr::all();
        $gln = GLN::where('status', GLN::STATUS_APPROVED)->get();
//        $warning = Warning::where('gtin_code', $product->barcode)->first();

        $distributors = Distributor::all();
        $distributorsData = collect([]);
        $productId = SProduct::select('id')->where('gtin_code', $product->barcode)->first();
        $reports = MICheckReport::where('type', 1)->where('status', 0)->where('target', $product->barcode)->get();

        if ($productId) {
            $productId = $productId->id;

            $distributorsData = DistributorProduct::where('product_id', $productId)->get()->keyBy('distributor_id');
        }
        $currencies = Currency::all();

        return view('staff.management.product.form', compact('product', 'categories', 'selectedCategories', 'attributes', 'gln', 'messages', 'warning', 'currencies', 'reports', 'distributorsData', 'distributors'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $product = Product::findOrFail($id);
//
//        $this->validate($request, [
//            'images' => 'required',
//        ]);

        $data = $request->all();

        if(isset($data['images'])){
            $data['image'] = json_encode($data['images']);
        }else{
            $data['image'] = null;
        }


        if (isset($data['warning_id'])) {
            ProductMessage::create([
                'gtin_code' => $product->gtin_code,
                //'gln_code' => @$product->gln->gln,
                'message_id' => $data['warning_id'],
            ]);
        } else {
            ProductMessage::where('gtin_code', $product->gtin_code)->delete();
        }
//        $productId = SProduct::select('id')->where('gtin_code', $product->barcode)->first();
//
//        if ($productId) {
//            $productId = $productId->id;
//
//
//            foreach ($request->input('distributors', []) as $distributorId => $distributor) {
//                if (isset($distributor['enabled'])) {
//
//
//                    $a = DistributorProduct::firstOrCreate([
//                        'product_id' => $productId,
//                        'distributor_id' => $distributorId,
//                    ]);
//
//                    if (!isset($distributor['is_monopoly'])) {
//                        $distributor['is_monopoly'] = 0;
//                    }
//
//                    $a->update($distributor);
//                } else {
//                    DistributorProduct::where('product_id', $productId)->where('distributor_id', $distributorId)->delete();
//                }
//            }
//        }

        MICheckReport::whereIn('_id', $request->input('report_resolved', []))->update([
            'resolvedBy' => auth()->guard('staff')->user()->email,
            'status' => 1,
        ]);

        $data['attrs'] = [];
        if($data['highlight_content']){
            foreach ($data['highlight_content'] as $key => $content){
                if($content){
                    if($data['highlight_id'][$key]){
                        $data['attrs'][$data['highlight_id'][$key]] = $content;
                    }

                }
            }
        }
        $product->update($data);
        $product->gln()->associate($request->input('gln_id'));
        ProductCategory::where(['product_id' => $product->id])->delete();

        if(isset($data['categories'])){

            $result  = [];

            foreach ($data['categories'] as $id){
                $category = Category::find($id);
                $r = $this->getParent($category,[]);

                $r[] = intval($id);
                $result = array_unique( array_merge( $result , $r ) );
            }
            $data['categories'] = $result;

        }else{
            $data['categories'] = [];
        }
        if($data['categories']){
            foreach ($data['categories'] as $cat) {
                ProductCategory::create(['product_id' => $product->id, 'category_id' => $cat]);
            }
        }

        $product->save();

        return redirect()->route('Staff::Management::product@edit', $product->id)
            ->with('success', 'Đã cập nhật thông tin sản phẩm Sản phẩm');
    }

    public function analytics($id)
    {
        if (auth()->guard('staff')->user()->cannot('list-product')) {
            abort(403);
        }
        $product = Product::findOrFail($id);
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);
        $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();
        $attributes = ProductAttr::all();
        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();

        return view('staff.management.product.form', compact('product', 'categories', 'selectedCategories', 'attributes', 'gln'));
    }

    public function approve(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $ids = $request->input('selected');
        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $p) {
            $product = Product2::where('gtin_code',$p->barcode)->first();
            if(is_null($product)){
                $product = new Product2();
                $product->internal_code = 'ip_'.microtime(true) * 10000;
                $product->gtin_code = $p->barcode;
                $product->save();
            }
            if (!is_null($product)) {
                $data = [];

                $data['product_name'] = $p->name;
                if(empty($p->price)){
                    $data['price_default'] = null;
                }else{
                    $data['price_default'] = $p->price;
                }


                if($p->image){

                    $images_p = [];
                    $images_p = json_decode($p->image,true);
                    if(count($images_p)){
                        $data['image_default'] = $images_p[0];
                    }

                }
                if(json_decode($p->properties,true)){
                    $count_features = 0;
                    $features = [];
                    $properties = json_decode($p->properties,true);
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
                $product->update($data);
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
                    if (isset($images_p) and is_array($images_p)) {

                        foreach ($images_p as $image) {
                            if ($image != $product->image_default) {
                                $m->push('attachments',[
                                    'type' => 'image',
                                    'link' => $image,
                                ]);
                            }

                        }
                    }

                }else{
                    if (isset($images_p) and is_array($images_p)) {
                        $m = new PProduct();
                        $m->gtin_code = $product->gtin_code;
                        $m->internal_code = $product->internal_code;
                        $m->save();
                        foreach ($images_p as $image) {
                            if ($image != $product->image_default) {
                                $m->push('attachments',[
                                    'type' => 'image',
                                    'link' => $image,
                                ]);
                            }

                        }
                    }
                }
                
                $product->verify_owner = Product2::BUSINESS_VERIFY_OWNER;
                if (substr($p->gln->gln, 0, 3) !== 'ICK') {
                    $gln = Vendor::where('gln_code', $p->gln->gln)->first();

                    if ($gln) {
                        $product->vendor_id = $gln->id;
                        $product->save();
                    }
                }
                $c = ProductCategory::where('product_id', $p->id)->get()->lists('category_id')->toArray();
                $product->categories()->sync($c);
                $product->in_categories = implode(',',$c);

                if($p->attrs){
                    foreach ($p->attrs as $attr => $value) {

                        if($value!=null){
                            $content = str_replace(array("\n", "\r"), '', $value);
                            $count = ProductInfo::where('attribute_id', $attr)->where('product_id', $product->id)->count();
                            if ($count > 0) {
                                $product->attributes()->updateExistingPivot($attr, ['content' => nl2br($content), 'content_text' => strip_tags($content)  ,'short_content' => Str::words(strip_tags($content),300,'')]);
                            } else {
                                $info = new ProductInfo;
                                $info->product_id = $product->id;
                                $info->attribute_id = $attr;
                                $info->content = nl2br($content);
                                $info->content_text = strip_tags($content);
                                $info->short_content =  Str::words(strip_tags($content),300,'');
                                $info->save();
                            }

                        }
                    }
                }
                $product->save();
                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request('PUT', env('DOMAIN_API') . 'products/'. $product->id, [
                        'auth' => [env('USER_API'), env('PASS_API')],
                    ]);
                    $res = json_decode((string)$res->getBody());

                    if ($res->status != 200) return redirect()->back()->with('danger', 'cập nhật thông tin Sản phẩm bi loi khi dong bo redis');
                } catch (RequestException $e) {
                    return $e->getResponse()->getBody();
                }

                $p->reason = $request->input('reason');
                $p->is_exist = 1;
                $p->status = Product::STATUS_APPROVED;
                $p->save();
                Contribute::where('gtin_code',$product->gtin_code)->update(['status'=>Contribute::STATUS_DISAPPROVED]);
                ContributeProduct::whereIn('status',[ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->where('gtin',$product->gtin_code)->delete();
                \App\Models\Enterprise\MLog::create([
                    'email' => auth()->guard('staff')->user()->email,
                    'action' => 'Approve sản phẩm DN ' . $p->name . '(' . $p->barcode . ')',
                ]);


            }

            if($p->relate_product == 1) {
                if (isset($p->gln->business)) {
                    $business = $p->gln->business;
                    $start_date = $business->start_date;
                    $end_date = $business->end_date;
                    if ($start_date and $end_date) {
                        $name = 'prod:' . $p->barcode;
                        $hook = Hook::firstOrCreate(['name' => $name]);
                        $hook->iql = null;
                        $hook->type = 0;
                        $hook->save();
                        $list_gtin = Product::where('gln_id', $p->gln_id)->where('is_exist', 1)->get()->lists('barcode');
                        foreach ($list_gtin as $gtin) {
                            $hook_product = HookProduct::firstOrCreate(['hook_id' => $hook->id, 'product_id' => $gtin]);
                            $hook_product->start_date = Carbon::createFromTimestamp(strtotime($start_date));
                            $hook_product->end_date = Carbon::createFromTimestamp(strtotime($end_date));
                            $hook_product->save();
                        }
                    }
                    $p->relate_product = 0;
                    $p->save();
                }
            }
        }
        return redirect()->back()
            ->with('success', 'OK');
    }

    public function disapprove($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }
        $product = Product::findOrFail($id);
        $product->reason = $request->input('reason');
        $product->status = Product::STATUS_DISAPPROVED;
        $exist = SProduct::where('gtin_code',$product->barcode)->count();
        if($exist == 0){
            $product->is_exist = 0;
        }
        $product->save();
        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Disapprove sản phẩm DN ' . $product->name . '(' . $product->barcode . ')',
        ]);
        return redirect()->back()
            ->with('success', 'Đã không chấp nhận Sản phẩm ' . $product->name . '');
    }

    public function inline($id,Request $request){
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }
        $product = Product::find($id);
        if ($request->input('name')) {
            if($request->input('name')=='dell-all-1994'){
                $product->name='';
            }else{
                $product->name = $request->input('name');
            }
        }
        if ($request->input('price') !=null) {
            $product->price = $request->input('price');
        }
        if($request->input('attr-1')!=null){

            $attrs = $product->attrs;

            $attrs[1] = $request->input('attr-1');
            $product->attrs = $attrs;

        }
        if($request->input('categories')!=null){
             $categories = json_decode($request->input('categories'));
            ProductCategory::where('product_id',$product->id)->delete();

            if($categories){

                $result  = [];

                foreach ($categories as $id){
                    $category = Category::find($id);
                    $r = $this->getParent($category,[]);

                    $r[] = intval($id);
                    $result = array_unique( array_merge( $result , $r ) );
                };
                if($result){
                    foreach ($result as $cat) {
                        ProductCategory::create(['product_id' => $product->id, 'category_id' => $cat]);
                    }
                }
            }


            $product->save();
        }
        $product->save();
        return 'oke';
    }
    public function disapproveAll(Request $request){
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }
        $reason = $request->input('reason');
        $selected = $request->input('selected');

        foreach ($selected as $id){

            $product = Product::findOrFail($id);
            $product->reason = $request->input('reason');
            $product->status = Product::STATUS_DISAPPROVED;
            $product->save();
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Disapprove sản phẩm DN ' . $product->name . '(' . $product->barcode . ')',
            ]);
        }
        return redirect()->back()
            ->with('success', 'Đã không chấp nhận Sản phẩm ');
    }



    // list product by business
    public function productByBusiness(Request $request,$id){
         $business = Business::findOrFail($id);

        if (auth()->guard('staff')->user()->cannot('product-by-business')) {
            abort(403);
        }


        $products = Product::select();

        if ($request->has('gln')) {
            $gln = GLN::where('gln', $request->input('gln'))->first();

            if ($gln) {
                $products = $products->where('gln_id', $gln->id);
            }
        }

        $gln = $business->gln()->where('status', GLN::STATUS_APPROVED)->get();
        $gln_id = $gln->lists('id')->toArray();

        if ($request->has('gln')) {
            $gln = GLN::where('gln', $request->input('gln'))->first();

            if ($gln) {
                $products = $products->where('gln_id', $gln->id);
            }
        }else{
            $products = $products->whereIn('gln_id', $gln_id);
        }
        $products = $products->orderBy('updated_at', 'desc')->paginate(10);
        $cat = [];

        foreach ($products as $product) {
            $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();

            $categories = Category::whereIn('id', $selectedCategories)->get()->lists('id')->toArray();

            $cat[$product->id] = $categories;
        }
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);

        $quota = 0;
        $role = $business->roles()->first();
        if($role){
            if($role->quota){
                $quota = $role->quota;
            }
        }
        $countSx = Product::whereIn('gln_id', $gln_id)->where('is_quota',1)->count();
        $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
        $totalProduct = $countPP+$countSx;


        return view('staff.management.product.productByBusiness', compact('products', 'cat','categories','business','quota','totalProduct'));

    }

    public function addQuotaProduct(Request $request,$id){

        $this->validate($request,[
           'gtins' => 'required'
        ],[
            'gtins.required' => 'Vui lòng nhâp list gtin'
        ]);
        $business = Business::find($id);
        $gln = $business->gln()->where('status',GLN::STATUS_APPROVED)->get()->lists('id')->toArray();
        $gtins = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtins'));

        $role = $business->roles()->first();
        if($role){

            if($role->quota){
                $quota = $role->quota;
                $countSx = Product::whereIn('gln_id', $gln)->where('is_quota',1)->count();
                $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
                $exist =  $quota-$countPP-$countSx;
                if(count($gtins) > $exist){
                    return redirect()->back()->with('error','Lượng quota còn lại không đủ.Chỉ còn '.$exist);
                }
                if($exist > 0 ){
                    Product::whereIn('gln_id', $gln)->where('is_exist',1)->whereIn('barcode',$gtins)->where('is_quota',0)->update(['is_quota' => 1]);
                    return redirect()->back()->with('success','Set sản phẩm trong quota thành công');
                }
            }
        }else{
            return redirect()->back()->with('error','Doanh nghiệp chưa được set role');
        }
    }

    public function removeQuotaProduct(Request $request,$id){
        $this->validate($request,[
            'gtins' => 'required'
        ],[
            'gtins.required' => 'Vui lòng nhâp list gtin'
        ]);

        $business = Business::find($id);
        $gln = $business->gln()->where('status',GLN::STATUS_APPROVED)->get()->lists('id')->toArray();
        $gtins = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtins'));

        Product::whereIn('gln_id', $gln)->where('is_exist',1)->whereIn('barcode',$gtins)->where('is_quota',1)->update(['is_quota' => 0]);
        return redirect()->back()->with('success','Xóa sản phẩm trong quota thành công');
    }



    public static function getParent($category,$parent){
        $c_parent = Category::find($category->parent_id);
        if($c_parent->parent_id == 12){
            $parent[] = $c_parent->id;
            return $parent;
        }
        $parent[] = $c_parent->id;
        return static::getParent($c_parent,$parent);
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
