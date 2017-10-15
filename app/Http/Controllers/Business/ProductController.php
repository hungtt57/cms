<?php

namespace App\Http\Controllers\Business;

use App\Models\Enterprise\ProductDistributor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\Product;
use App\Models\Enterprise\ProductCategory;
use App\Models\Enterprise\MStaffNotification;
use App\Models\Enterprise\ProductDistributorTemp;

use App\Models\Icheck\Product\Product as Product2;
use App\Models\Icheck\Product\Category;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Icheck\Product\ProductInfo;
use App\Models\Mongo\Product\PComment as Comment;

use GuzzleHttp\Exception\RequestException;
use Auth;
use Event;
use App\Events\BusinessProductsFileUploaded;
use Illuminate\Support\MessageBag;
use App\Models\Mongo\Product\PProduct;
use DB;
use App\Events\FileEditProductDistributor;
use App\Models\Icheck\User\Account;
use \Carbon\Carbon;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use App\Jobs\AddRelateProductPPJob;
use App\Models\Icheck\Product\AttrValue;
class ProductController extends Controller
{
    public function index(Request $request)
    {

        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
        $list_gln = clone $gln;
        $gln2 = $gln->lists('id', 'gln')->toArray();
        $gln3 = $gln->lists('gln')->toArray();
        $gln = $gln->lists('id')->toArray();

        $vendors = Vendor::whereIn('gln_code', $gln3)->get();

        $productsEx = Product::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();

            foreach ($vendors as $vendor) {
                $products = $vendor->products()->where('status',Product::STATUS_APPROVED)->whereNotIn('gtin_code', $productsEx)->get();
                $attrs = ProductAttr::lists('id')->toArray();

                foreach ($products as $product) {
                    $newProduct = Product::firstOrCreate([
                        'barcode' => $product->gtin_code,
                    ]);
                    $image_p = null;
                    $array_image = [];
                    if ($product->image_default != '') {
                        $array_image[] =  $product->image_default;
                    }
                    if($product->pproduct && isset($product->pproduct->attachments)){

                        foreach ($product->pproduct->attachments as $value){

                            if(isset($value['type'])) {
                                if ($value['type'] == 'image') {
                                    $array_image[]  = $value['link'];

                                }
                            }
                        }
                    }
                    if($array_image){
                        $image_p = json_encode($array_image);
                    }

                    $data = [
                        'name' => $product->product_name,
                        'image' => $image_p,
                        'price' => $product->price_default,
                        'status' => Product::STATUS_APPROVED,
                    ];

                    $infos = ProductInfo::whereIn('attribute_id', $attrs)->where('product_id', $product->id)->get();
                    $infos = $infos->lists('content', 'attribute_id')->toArray();

                    $data['attrs'] = $infos;

                    $newProduct->update($data);
                    $newProduct->gln()->associate($gln2[$vendor->gln_code]);

                    foreach ($product->categories()->get() as $cat) {
                        ProductCategory::firstOrCreate(['product_id' => $newProduct->id, 'category_id' => $cat->id]);
                    }

                    $newProduct->save();


                    $name = 'prod:' . $newProduct->barcode;
                    $iql = 'Product.find({vendor_id:[' . $vendor->id. ']})';
                    $hook = Hook::firstOrCreate(['name' => $name]);
                    $hook->iql = $iql;
                    $hook->type = 2;
                    $hook->save();

                    HookProduct::where('hook_id',$hook->id)->delete();
                }
            }

        $business = Auth::user();
        //check add quota
        $role = $business->roles()->first();
        $quota = 0;
        $totalProduct = 0 ;
        if($role){
//            if($role->quota){
//                $quota = $role->quota;
//                $countSx = Product::whereIn('gln_id', $gln)->where('is_quota',1)->count();
//                $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
//                $exist =  $quota-$countPP-$countSx;
//                if($exist > 0 ){
//                    Product::whereIn('gln_id', $gln)->where('is_exist',1)->where('is_quota',0)->limit($exist)->update(['is_quota' => 1]);
//                }
//                if($exist < 0 ){
//                    $exist = 0 - $exist;
//                    Product::whereIn('gln_id', $gln)->where('is_exist',1)->where('is_quota',1)->limit($exist)->update(['is_quota' => 0]);
//                }
//            }else{
//                Product::whereIn('gln_id', $gln)->update(['is_quota' => 0]);
//            }
        }
        $countSx = Product::whereIn('gln_id', $gln)->where('is_quota',1)->count();
        $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
        $totalProduct = $countPP+$countSx;

        if ($request->input('gln')) {

            $products = Product::where('gln_id', $request->input('gln'))->with('gln');
        } else {
            $products = Product::whereIn('gln_id', $gln)->with('gln');
        }


        if ($request->input('q')) {
            $products = $products->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('barcode', $request->input('q'));
            });
        }
        if ($request->input('status') != null && $request->input('status') != 4) {
            $products = $products->where('status', $request->input('status'));
        }

        //end check quota
        $products = $products->orderBy('is_quota', 'desc')->orderBy('updated_at', 'desc')->paginate(10);
        return view('business.product.index', compact('products', 'counts', 'list_gln','quota','totalProduct'));
    }

    public function import(Request $request)
    {
        if ($request->file('file')->isValid()) {
            $filename = time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/products'), $filename);

            Event::fire(new BusinessProductsFileUploaded(auth()->user()->id, storage_path('app/import/products/' . $filename)));
        }

        return redirect()->back()
            ->with('success', 'File đã được lên lịch import');
    }

    public function importDistributor(Request $request)
    {
        if ($request->file('file')->isValid()) {
            $filename = time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/products'), $filename);

            Event::fire(new FileEditProductDistributor(auth()->user()->id, storage_path('app/import/products/' . $filename)));
        }

        return redirect()->back()
            ->with('success', 'File đã được lên lịch import');
    }

    protected function r($data, $parent = 0, $level = 0)
    {
        $list = [];

        if (isset($data[$parent])) {
            foreach ($data[$parent] as $cat) {
                $cat->level = $level;
                $list[] = $cat;

                foreach ($this->r($data, $cat['id'], $level + 1) as $subCat) {
                    $list[] = $subCat;
                }
            }
        }

        return $list;
    }

    public function add()
    {
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);
        $attributes = ProductAttr::all();
        $selectedCategories = [];
        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
//        $gln = Auth::user()->gln;
        if(Auth::user()->id== 1){
            return view('business.product.form2', compact('categories', 'attributes', 'gln','selectedCategories'));
        }
        return view('business.product.form', compact('categories', 'attributes', 'gln','selectedCategories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'barcode' => 'required',
            'images' => 'required',
            'gln_id' => 'required',
            'check_code' => 'required',
            'prefix' => 'required'
        ],
            [
                'gln_id.required' => 'Vui lòng chọn nhà sản xuất',
                'check_code.required' => 'Vui lòng lấy mã kiểm tra',
                'prefix.required' => 'Vui lòng chọn mã prefix',
                'barcode.required' => 'Vui lòng nhập mã phân định sản phẩm',
                'images.required' => 'Vui lòng nhập ảnh sản phẩm'
            ]);


        $data = $request->all();
        $gln = GLN::find($request->input('gln_id'));
        if ($data['prefix'] != $gln->prefix) {
            return redirect()->back()->with('error', 'Mã Prefix không đúng với GLN đã chọn.')->withInput();
        }
        $length = strlen($data['prefix']) + strlen($data['barcode']);

        if ($length != 12) {
            return redirect()->back()->with('error', 'Vui lòng nhập mã phân định có độ dài là : ' . (12 - strlen($data['prefix']) . ' kí tự'))->withInput();
        }
        $checkCode = $this->getCheckCode($data['prefix'] . $data['barcode']);
        if ($checkCode != $data['check_code']) {
            return redirect()->back()->with('error', 'Mã kiểm tra không đúng.')->withInput();
        }


        $data['barcode'] = $data['prefix'] . $data['barcode'] . $data['check_code'];
        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
        $gln = $gln->lists('id')->toArray();
        $countProduct = Product::whereIn('gln_id', $gln)->where('barcode',$data['barcode'])->get()->count();
        if($countProduct){
            return redirect()->back()->with('error', 'Mã barcode đã tồn tại của doanh nghiệp')->withInput();
        }

        $count = Product2::where('gtin_code', $data['barcode'])->where('vendor_id','!=',null)->count();
        if ($count > 0) {
            return redirect()->back()->with('error', 'Mã barcode đã tồn tại trên hệ thống')->withInput();
        }

        if (isset($data['images'])) {
            $data['image'] = json_encode($data['images']);
        }

        $data['status'] = Product::STATUS_PENDING_APPROVAL;

//        if ($data['barcode'] and !validate_barcode($data['barcode'])) {
//            $data['warning'] = 'Có vẻ như mã vạch không đúng định dạng';
//        } else {
//            $data['warning'] = '';
//        }
            //new
//        $data['attrs'] = [];
//        if($data['highlight_content']){
//            foreach ($data['highlight_content'] as $key => $content){
//                if($content){
//                    if($data['highlight_id'][$key]){
//                        $data['attrs'][$data['highlight_id'][$key]] = $content;
//                    }
//
//                }
//            }
//        }
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
        $product->save();
        $notification = new MStaffNotification();
        $notification->content = '<strong>' . Auth::user()->name . '</strong> đã yêu cầu thêm sản phẩm <strong>' . $product->name . '</strong>';
        $notification->type = MStaffNotification::TYPE_BUSINESS_ADD_PRODUCT;
        $notification->data = [
            'business' => Auth::user()->toArray(),
            'product' => $product->toArray(),
        ];
        $notification->unread = true;
        $notification->save();
        return redirect()->route('Business::product@index')
            ->with('success', 'Đã thêm Sản phẩm');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        if ($product->status == Product::STATUS_APPROVED or $product->status== Product::STATUS_DISAPPROVED) {
            $po = Product2::where('gtin_code', $product->barcode)->first();
            if ($po) {
                $attrs = ProductAttr::lists('id')->toArray();

                $m = PProduct::where('gtin_code', $po->gtin_code)->first();
                $images = [];

                if ($po->image_default) {
                    $images[] = $po->image_default;
                }

                if ($m && isset($m->attachments)) {
                    foreach ($m->attachments as $key => $image) {

                        if ($image['type'] == 'image') {
                            $images[] = $image['link'];
                        }
                    }
                }
                $data = [
                    'name' => $po->product_name,
                    'image' => json_encode($images),
                    'price' => $po->price_default,
                ];
                $infos = ProductInfo::whereIn('attribute_id', $attrs)->where('product_id', $po->id)->get();
                $infos = $infos->lists('content', 'attribute_id')->toArray();
                $data['attrs'] = $infos;
                $properties2 = $po->properties()->get();
                $properties = null;
                if($properties2){
                    foreach ($properties2 as $property){
                        if($property->content){
                            $v = explode(',',$property->content);
                            $properties[$property->attribute_id] = $v;
                        }
                    }
                    $properties = json_encode($properties);
                }
                $data['properties'] = $properties;
                $product->update($data);
                foreach ($po->categories()->get() as $cat) {
                    ProductCategory::firstOrCreate(['product_id' => $product->id, 'category_id' => $cat->id]);
                }

                $product->save();
            }
        }
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);
        $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();
        $attributes = ProductAttr::all();
        $gln = Auth::user()->gln;
        if(Auth::user()->id == 1){
            return view('business.product.form2', compact('product', 'categories', 'selectedCategories', 'attributes', 'gln'));
        }
        return view('business.product.form', compact('product', 'categories', 'selectedCategories', 'attributes', 'gln'));
    }

    public function update($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'images' => 'required',
        ], [
            'name.required' => 'Vui lòng chọn tên',
            'images.required' => 'Vui lòng nhập ảnh sản phẩm'
        ]);
        $data = $request->all();
        $data['image'] = json_encode($data['images']);
        $data['status'] = Product::STATUS_PENDING_APPROVAL;
        if(isset($data['properties'])){
            foreach ($data['properties'] as $key => $value){
               if(isset($value[0]) and $value[0]){
                    continue;
               }else{
                   unset($data['properties'][$key]);
               }
            }
            $data['properties'] = json_encode($data['properties']);
        }
//        $data['attrs'] = [];
//        if($data['highlight_content']){
//            foreach ($data['highlight_content'] as $key => $content){
//                if($content){
//                    if($data['highlight_id'][$key]){
//                        $data['attrs'][$data['highlight_id'][$key]] = $content;
//                    }
//
//                }
//            }
//        }
        $product->update($data);
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
        $notification = new MStaffNotification();
        $notification->content = '<strong>' . Auth::user()->name . '</strong> đã yêu cầu cập nhật thông tin sản phẩm <strong>' . $product->name . '</strong>';
        $notification->type = MStaffNotification::TYPE_BUSINESS_UPDATE_PRODUCT;
        $notification->data = [
            'business' => Auth::user()->toArray(),
            'product' => $product->toArray(),
        ];
        $notification->unread = true;
        $notification->save();
        return redirect()->route('Business::product@index');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->status = Product::STATUS_PENDING_DELETE;
        $product->save();
        $notification = new MStaffNotification();
        $notification->content = '<strong>' . Auth::user()->name . '</strong> đã yêu cầu xoá sản phẩm <strong>' . $product->name . '</strong>';
        $notification->type = MStaffNotification::TYPE_BUSINESS_DELETE_PRODUCT;
        $notification->data = [
            'business' => Auth::user()->toArray(),
            'product' => $product->toArray(),
        ];
        $notification->unread = true;
        $notification->save();

        return redirect()->back()->with('success', 'Đã gửi yêu cầu xoá sản phẩm');
    }

    public function analytics($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);
        $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();
        $attributes = ProductAttr::all();
        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();

        return view('business.product.form', compact('product', 'categories', 'selectedCategories', 'attributes', 'gln'));
    }

    public function comments($gtin)
    {
        if (auth()->user()->cannot('view-comment')) {
            abort(403);
        }
        $account = null;
        $icheck_id = Auth::user()->icheck_id;
        if ($icheck_id) {
            $account = Account::where('icheck_id', $icheck_id)->first();
        }

        $product = Product2::where('gtin_code', $gtin)->first();

        if(empty($product)){
            return redirect()->back()->with('error','MÃ sản phẩm này chưa tồn tại trên hệ thống!');
        }
        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
        $gln = $gln->lists('id')->toArray();
        $productsEx = Product::whereIn('gln_id', $gln)->where('barcode','like','%'.$gtin.'%')->first();
        if(empty($productsEx)){
            $productPP = ProductDistributor::where('business_id', Auth::user()->id)->where('product_id',$product->id)->first();
            if(empty($productPP)){
                return redirect()->route('Business::product@listProductDistributor')->with('error', 'Sản phẩm chưa được đăng kí phân phối!!');
            }elseif($productPP){
                if($productPP->is_quota == 0){
                    return redirect()->route('Business::product@listProductDistributor')->with('error', 'Không có quyền xem comment mã sản phẩm này!!');
                }
            }else{
                return redirect()->route('Business::product@index')->with('error', 'Mã sản phẩm không thuộc quyền sản xuất của bạn!!');
            }
        }else{
            if($productsEx->is_quota == 0){
                return redirect()->route('Business::product@index')->with('error', 'Không có quyền xem comment mã sản phẩm này!!');
            }
        }

        $comments = Comment::where('object_id', $gtin)->where('parent', '')->where('deleted_at',null)->orderBy('createdAt', 'desc')->get();
        return view('business.product.comments', compact('comments', 'product', 'account', 'gtin'));
    }

    public function answerComment(Request $request)
    {
        $icheck_id = Auth::user()->icheck_id;
        if ($icheck_id) {
            $account = Account::where('icheck_id', $icheck_id)->first();
            $content = $request->input('content');
            $parent_id = $request->input('parent_id');
            $gtin_code = $request->input('gtin_code');
            if (trim($content) == '') {
                return response()->json(['message' => 'Vui lòng nhập nội dung'], 400);
            }
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('POST', env('DOMAIN_API') . 'comments', [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'icheck_id' => $icheck_id,
                        'object_id' => $gtin_code,
                        'content' => $content,
                        'parent' => $parent_id
                    ]
                ]);

                $res = json_decode((string)$res->getBody());

            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $template = ' <div class="media"><div class="media-left">';
            if (isset($res->data->owner->social_id)) {
                $template .= '  <img src="http://graph.facebook.com/' . $res->data->owner->social_id . '/picture"
                                                                 class="img-circle" alt=""> ';
            } else {
                $template .= '<img src="' . public_path("assets/images/image.png") . 'class="img-circle" alt="">';
            }

            $template .= '</div><div class="media-body"><h6 class="media-heading"><strong class="js-actor-name">' . $account->name . '</strong></h6> <p class="js-comment-content">' . $content . '</p>';

            $template .= '<div class="media-annotation mt-5 js-action-time">   <div class="col-md-3">' . Carbon::createFromTimestamp(round($res->data->createdAt / 1000))->toDateTimeString() . '</div><div class="col-md-9 answer"> <button type="button" class="btn text-slate-800 btn-flat button-delete" data-url="' . route("Business::product@deleteComment", ["id" => $res->data->id]) . '" data-id="' . $res->data->id . '">Xóa<span class="legitRipple-ripple"></span></button></div><div style="clear:both"></div></div></div></div>';
            return $template;
        }

    }

    public function addComment(Request $request)
    {
        $icheck_id = Auth::user()->icheck_id;
        if ($icheck_id) {
            $account = Account::where('icheck_id', $icheck_id)->first();
            $content = $request->input('content');
            $gtin_code = $request->input('gtin_code');

            if (trim($content) == '') {
                return response()->json(['message' => 'Vui lòng nhập nội dung'], 400);
            }
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('POST', env('DOMAIN_API') . 'comments', [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'icheck_id' => $icheck_id,
                        'object_id' => $gtin_code,
                        'content' => $content,
                        'parent' => ''
                    ]
                ]);

                $res = json_decode((string)$res->getBody());

            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $comment = $res->data;
            $comment = Comment::where('_id', $comment->id)->first();
            return view('business.product.ajaxComment', compact('comment', 'account'))->render();
        }
    }

    public function deleteComment($id, Request $request)
    {
        $comment = Comment::where('_id', $id)->first();
        $icheck_id = Auth::user()->icheck_id;
        if ($comment) {

            $iComment = $comment->owner['icheck_id'];
            if ($icheck_id != $iComment) {
                return redirect()->back()->with('error', 'Bạn không có quyền xóa comment này');
            }

            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->delete(env('DOMAIN_API').'comments/' . $id, [
                    'auth' => [env('USER_API'), env('PASS_API')],
                ]);

                $res = json_decode((string)$res->getBody());
                if ($res->status != 200) {
                    return redirect()->back()->with('error', 'Server bị lỗi! Vui lòng thử lại sau');
                }
                return redirect()->back()->with('success', 'Xóa thành công!');
            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'Server bị lỗi! Vui lòng thử lại sau');

            }
        } else {
            return redirect()->back()->with('error', 'Comment không tồn tại');
        }
    }
    public function pinComment($id,Request $request){
        $comment = Comment::where('_id', $id)->first();

        $icheck_id = Auth::user()->icheck_id;

        if ($comment) {

            $iComment = $comment->owner['icheck_id'];
            if ($icheck_id != $iComment) {
                return redirect()->back()->with('error', 'Bạn không có quyền Gim comment này');
            }

            $pinComment = Comment::where('score',1)->where('object_id',$comment->object_id)->where('deleted_at',null)->first();
            if($pinComment){
                $pinComment->score = 0;
                $pinComment->save();
            }
            $comment->score = 1;
            $comment->save();
            return redirect()->back()->with('success', 'Gim thành công!');
        } else {
            return redirect()->back()->with('error', 'Comment không tồn tại');
        }


    }
    public function unpinComment(Request $request,$id){
        $comment = Comment::where('_id', $id)->first();

        $icheck_id = Auth::user()->icheck_id;

        if ($comment) {

            $iComment = $comment->owner['icheck_id'];
            if ($icheck_id != $iComment) {
                return redirect()->back()->with('error', 'Bạn không có quyền UNPIN comment này');
            }

            $comment->score = 0;
            $comment->save();
            return redirect()->back()->with('success', 'Bỏ Gim thành công!');
        } else {
            return redirect()->back()->with('error', 'Comment không tồn tại');
        }

    }

    public function listProductDistributor(Request $request)
    {
        $business = Auth::user();
            $quota = 0;
        $totalProduct = 0;
            $role = $business->roles()->first();
            if($role){
                $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
                $gln = $gln->lists('id')->toArray();
//                if($role->quota){
//                    $quota = $role->quota;
//                    $countSx = Product::whereIn('gln_id', $gln)->where('is_quota',1)->count();
//                    $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
//                    $exist =  $quota-$countPP-$countSx;
//                    if($exist > 0 ){
//                        ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',0)->limit($exist)->update(['is_quota' => 1]);
//                    }
//                    if($exist < 0 ){
//                        $exist = 0 - $exist;
//                        ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->limit($exist)->update(['is_quota' => 0]);
//                    }
//
//                    $countSx = Product::whereIn('gln_id', $gln)->where('is_quota',1)->count();
//                    $countPP = ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->where('is_quota',1)->count();
//                    $totalProduct = $countSx + $countPP;
//                }else{
//                    ProductDistributor::where('business_id',$business->id)->where('status',ProductDistributor::STATUS_ACTIVATED)->update(['is_quota' => 0]);
//                }
            }



        $filter = 1;
        if ($request->input('filter')) {
            $filter = $request->input('filter');
            if ($filter == 2) {
                $products = Auth::user()->productsDistributor(1);
            } elseif ($filter == 3) {
                $products = Auth::user()->productsDistributor(0);
            } else {
                $products = Auth::user()->productsDistributor();
            }
        } else {
            $products = Auth::user()->productsDistributor();
        }
        if ($request->input('q')) {
            $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();
            $gln = $gln->lists('id')->toArray();

            $barcodes = Product::whereIn('gln_id', $gln)->get(['barcode'])->lists('barcode')->toArray();

            $products = Product2::where(function ($query) use ($request, $barcodes) {
                $query->where('product_name', 'like', '%' . $request->input('q') . '%')
                    ->orWhere('gtin_code', 'like', '%' . $request->input('q') . '%')->whereNotIn('gtin_code', $barcodes);
            });
        }
        if ($request->input('status') != null) {
            $status = $request->input('status');
            if ($status != 4) {
                $product_temp = ProductDistributorTemp::where('business_id', Auth::user()->id)->where('status', $status)->get()->lists('gtin_code')->toArray();
                $products = Product2::whereIn('gtin_code', $product_temp);

            }

        }

        $products = $products->paginate(15);
        return view('business.distributor.index', compact('products', 'filter','quota','totalProduct'));
    }

    public function postRegisterProduct(Request $request)
    {
        $product_id = $request->input('product_id');
        if (!empty($product_id)) {
            $product_ids = explode(',', $product_id);
            $business = Auth::user();
            DB::beginTransaction();

            foreach ($product_ids as $value) {
                try {
                    $is_first = 0;
                    $count = ProductDistributor::where('product_id', $value)->where('is_first', 1)->count();

                    if ($count == 0) {
                        $is_first = 1;
                    }
                    $pD = ProductDistributor::firstOrCreate(['business_id' => $business->id, 'product_id' => $value]);
                    if ($pD->is_first != 1) {
                        $pD->is_first = $is_first;
                    }
                    $pD->status = ProductDistributor::STATUS_ACTIVATED;
                    $pD->save();
                    DB::commit();
                } catch (\Exception $ex) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Server bị lỗi !! Vui lòng thử lại sau');
                }

            }
            return redirect()->route('Business::product@listProductDistributor')->with('success', 'Đăng kí phân phối thành công');
        } else {
            return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm muốn phân phối');
        }

    }

    public function registerDistributor(Request $request)
    {
        $id = $request->input('id');
        $is_first = 0;
        $business = Auth::user();
        $count = ProductDistributor::where('product_id', $id)->where('is_first', 1)->count();

        if ($count == 0) {
            $is_first = 1;
        }
        $pD = ProductDistributor::firstOrCreate(['business_id' => $business->id, 'product_id' => $id]);
        if ($pD->is_first != 1) {
            $pD->is_first = $is_first;
        }
        $pD->status = ProductDistributor::STATUS_ACTIVATED;
        $pD->save();

            if($pD->is_first == 1){
                $products =  ProductDistributor::where('business_id', $business->id)->where('is_first', 1)->where('product_id','!=',$pD->product_id)->get();
                if($products){

                    $this->dispatch(new AddRelateProductPPJob($products, $pD,Auth::user()));


                }
            }


        return redirect()->route('Business::product@listProductDistributor')->with('success', 'Đăng kí phân phối thành công');
    }

    public function cancelProduct(Request $request)
    {
        $id = $request->input('id');
        $business = Auth::user();
        DB::beginTransaction();

        try {
            $gtin_code = Product2::find($id)->gtin_code;
            $pD =   ProductDistributor::where('product_id', $id)->where('business_id', $business->id);
            $delete = clone $pD;
            $pD = $pD->first();

            if($pD->is_first == 1){
                $hook = Hook::where('name','like','%'.$gtin_code)->first();
                if($hook){
                    HookProduct::where('hook_id', $hook->id)->delete();
                    HookProduct::where('product_id', $gtin_code)->delete();
                }
            }
            $delete->delete();

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('Business::product@listProductDistributor')->with('error', 'Hệ thống đang lỗi! Vui lòng thử lại sau');
        }
        return redirect()->route('Business::product@listProductDistributor')->with('success', 'Hủy phân phối thành công');
    }

    public function editProduct(Request $request)
    {
        $id = $request->input('id');
        $pD = ProductDistributor::where('business_id', Auth::user()->id)->where('product_id', $id)->first();

        if (empty($pD)) {
            return redirect()->route('Business::product@listProductDistributor')->with('error', 'Doanh nghiệp không có quyền sửa sản phẩm');
        } else if ($pD->is_first == 0 | $pD->status != ProductDistributor::STATUS_ACTIVATED) {
            return redirect()->route('Business::product@listProductDistributor')->with('error', 'Doanh nghiệp không có quyền sửa sản phẩm');
        }

        $product = Product2::findOrFail($id);

        $attributes = ProductAttr::all();


        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);

        $selectedCategories = $product->categories->lists('id')->toArray();

        $m = PProduct::where('gtin_code', $product->gtin_code)->first();
        $images = [];

        if ($product->image_default) {
            $images[$product->image_default] = ['default' => true, 'prefix' => $product->image_default];
        }

        if ($m && isset($m->attachments)) {
            foreach ($m->attachments as $key => $image) {

                if ($image['type'] == 'image') {
                    $images[$key] = ['default' => false, 'prefix' => $image['link']];
                }

            }


        }
        return view('business.distributor.edit', compact('product', 'attributes', 'categories', 'selectedCategories', 'images'));
    }

    public function updateProduct($id, Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required',
        ]);


        $data = $request->all();
        $product = ProductDistributorTemp::firstOrCreate(['gtin_code' => $request->input('gtin_code'), 'business_id' => Auth::user()->id]);


        if (isset($data['images'])) {
            $data['image'] = json_encode($data['images']);
        } else {
            $data['image'] = null;
        }

        $data['business_id'] = Auth::user()->id;

        $data['status'] = ProductDistributorTemp::STATUS_PENDING_APPROVAL;

        $data['attrs'] = json_encode($data['attrs']);
        if(isset($data['categories'])){

            $result  = [];

            foreach ($data['categories'] as $id){
                $category = Category::find($id);
                $r = $this->getParent($category,[]);

                $r[] = intval($id);
                $result = array_unique( array_merge( $result , $r ) );
            }
            $data['categories'] = $result;
            $data['categories'] = json_encode($data['categories']);
        }else{
            $data['categories'] = null;
        }
        if(isset($data['properties'])){
            foreach ($data['properties'] as $key => $value){
                if(isset($value[0]) and $value[0]){
                    continue;
                }else{
                    unset($data['properties'][$key]);
                }
            }
            $data['properties'] = json_encode($data['properties']);
        }
        $product->update($data);

        $notification = new MStaffNotification();
        $notification->content = '<strong>' . Auth::user()->name . '</strong> đã yêu cầu sửa sản phẩm phân phối ';
        $notification->type = MStaffNotification::TYPE_BUSINESS_EDIT_PRODUCTPP_FILE;
        $notification->data = null;
        $notification->unread = true;
        $notification->save();

        return redirect()->route('Business::product@listProductDistributor')->with('success', 'Đăng kí sửa thành công');

    }


    public function ajaxAutoGenerate(Request $request)
    {
        $this->validate($request, [
            'prefix' => 'required'
        ]);
        $prefix = $request->input('prefix');
        while (true) {
            $barcode = $this->random($prefix);
            $count = Product2::where('gtin_code', $barcode)->count();
            if ($count == 0) {
                break;
            }
        }
        $checkCode = $this->getCheckCode($prefix . $barcode);
        return ['barcode' => $barcode, 'checkCode' => $checkCode];

    }

    public function ajaxGetCheckCode(Request $request)
    {
        $prefix = $request->input('prefix');
        $barcode = $request->input('barcode');
        $checkCode = $this->getCheckCode($prefix . $barcode);
        return ['checkCode' => $checkCode];
    }

    private function random($prefix)
    {
        $len = 13 - strlen($prefix) - 1;

        $string = null;
        for ($i = 1; $i <= $len; $i++) {
            $number = rand(0, 9);
            $string = $string . $number;
        }
        return $string;
    }

    private function getCheckCode($barcode)
    {
        $chars = str_split($barcode);
        $le = 0;
        $chan = 0;
        for ($i = 0; $i < 12; $i++) {
            if (($i + 1) % 2 == 1) {
                $le = $le + intval($chars[11 - $i]);
            } else {
                $chan = $chan + intval($chars[11 - $i]);
            }
        }
        $le = $le * 3;
        $total = $le + $chan;


        if ($total % 10 == 0) {
            return 0;
        } else {
            $a = (intval($total / 10) + 1) * 10;
            $checkCode = $a - $total;
            return $checkCode;
        }

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
}
