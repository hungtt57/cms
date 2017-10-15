<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\ProductCategory;
use App\Models\Enterprise\MICheckReport;
use App\Models\Social\MNotification;
use App\Models\Social\MConfig;
use App\Models\Social\MProductRelated;

use GuzzleHttp\Exception\RequestException;

use Auth;
use Event;
use App\Events\ProductsFileUploaded;
use Cache;
use \Firebase\JWT\JWT;
use Carbon\Carbon;
use App\Models\Collaborator\ContributeProduct;
use App\Models\Icheck\Product\Product;
use App\Models\Icheck\Product\Distributor;
use App\Models\Icheck\Product\DistributorProduct;
use App\Models\Icheck\Product\Category;
use App\Models\Icheck\Product\Currency;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Mongo\Product\PProduct;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\Contribute;
use App\Models\Icheck\Product\Message;
use App\Models\Icheck\Product\ProductMessage;

use App\Models\Icheck\Product\ProductInfo;

use App\Models\Icheck\Product\ProductReport;
use App\Models\Mongo\Social\ICheckReport;

use App\Jobs\ApproveListContributeUserJob;
use App\Jobs\RemoveFieldProductJob;
use App\Models\BarcodeViet\MSMVGTIN;
use App\Models\Icheck\Product\AttrValue;
use App\Models\Enterprise\Product as ProductDN;
use Illuminate\Support\Str;
use App\Jobs\ReportData;
use App\Transformers\Product2Transformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Models\Icheck\Product\AttrDynamic;
class Product2Controller extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-product')) {
            abort(403);
        }

        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);

        return view('staff.management.product2.index', compact('categories'));
    }

    public function index2(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-product')) {
            abort(403);
        }

        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);

        return view('staff.management.product2.index2', compact('categories'));
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

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-product')) {
            abort(403);
        }

        $messages = Message::all();

        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);
        $attributes = ProductAttr::all();
        $distributors = Distributor::all();
        $currencies = Currency::all();
        $selectedCategories = [];
        return view('staff.management.product2.form', compact('categories', 'selectedCategories', 'attributes', 'gln', 'messages', 'currencies', 'reports', 'distributorsData', 'distributors'));
    }

    public function adForm(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('products2-ad')) {
            abort(403);
        }

        $distributors = Distributor::all();

        return view('staff.management.product2.ad', compact('distributors'));
    }

    public function ad(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $gtin = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtin'));
        $gtin = Product::select(['id', 'gtin_code'])->whereIn('gtin_code', $gtin)->get();


        $distributors = $request->input('distributors', []);

        foreach ($gtin as $product) {
//            foreach ($request->input('agencies_selected', []) as $agencyId) {
//                $a = AgencyProduct::firstOrCreate([
//                    'product_id' => $product->id,
//                    'agency_id' => $agencyId,
//                ]);
//                $a->update([
//                    'price' => 0,
//                    'price_off' => 0,
//                    'currency_code' => 0,
//                    'price_vnd' => 0,
//                ]);
//            }

            foreach ($request->input('distributors_selected', []) as $distributorId) {
                $a = DistributorProduct::firstOrCreate([
                    'product_id' => $product->id,
                    'distributor_id' => $distributorId,
                ]);

                $distributor = [];

                if (isset($distributors[$distributorId])) {
                    $distributor = $distributors[$distributorId];
                    if (!isset($distributor['is_monopoly'])) {
                        $distributor['is_monopoly'] = 0;
                    }
                } else {
                    $distributor['is_monopoly'] = 0;
                }

                $a->update($distributor);
            }
        }

        return redirect()->back()
            ->with('success', 'Ok');
    }

    public function removeAForm(Request $request)
    {
        return view('staff.management.product2.rad');
    }

    public function removeDForm(Request $request)
    {

         if (auth()->guard('staff')->user()->cannot('products2-removeD')) {
             abort(403);
         }
        return view('staff.management.product2.rad');
    }

    public function removeA(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $gtin = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtin'));
        $gtin = Product::select(['id', 'gtin_code'])->whereIn('gtin_code', $gtin)->get();

        foreach ($gtin as $product) {
            AgencyProduct::where('product_id', $product->id)->delete();

        }

        return redirect()->back()
            ->with('success', 'Ok');
    }

    public function removeD(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $gtin = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtin'));
        $gtin = Product::select(['id', 'gtin_code'])->whereIn('gtin_code', $gtin)->get();

        foreach ($gtin as $product) {
            DistributorProduct::where('product_id', $product->id)->delete();

        }

        return redirect()->back()
            ->with('success', 'Ok');
    }

    public function export()
    {
        if (auth()->guard('staff')->user()->cannot('list-product')) {
            abort(403);
        }

        return 'Hệ thống đang tạo báo cáo. Bạn sẽ nhận được link tải về vào email <strong>' . auth()->guard('staff')->user()->email . '</strong> khi báo cáo sẵn sàng.';
    }

    public function import(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-product')) {
            abort(403);
        }

        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        if ($request->file('file')->isValid()) {
            $filename = time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/products'), $filename);

            $prefix = 1;
            $vendor = 1;
            if($request->input('prefix')){
                $prefix = 0;
            }
            if($request->input('vendor')){
                $vendor = 0;
            }
            $result = Event::fire(new ProductsFileUploaded(auth()->guard('staff')->user()->email, storage_path('app/import/products/' . $filename), $request->file('file')->getClientOriginalName(), $request->query('new', 0),$prefix,$vendor));

        }

        return redirect()->back()
            ->with('success', 'File đã được lên lịch import');
    }

    public function store(Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('add-product')) {
            abort(403);
        }
        $this->validate($request, [
            'gtin_code' => 'required|unique:icheck_product.product,gtin_code',
            'vendor' => 'required|exists:icheck_product.vendor,gln_code',
        ]);

        $data = $request->all();

        $data['internal_code'] = 'ip_' . microtime(true) * 10000;

        if (!isset($data['image_default'])) {
            if (isset($data['images']) and count($data['images']) > 0) {
                $data['image_default'] = $data['images'][0];
            } else {
                $data['image_default'] = '';
            }
        }

        if (isset($data['keywords']) and $data['keywords']!=null) {
            $keywords = implode(',', $data['keywords']);
            $data['keywords'] = $keywords;
        }
        if(isset($data['vendor'])){
            $data['vendor'] = trim($data['vendor']);
            if ($vendor = Vendor::where('gln_code', $data['vendor'])->first()) {
                $data['vendor_id'] = $vendor->id;
            }
        }
        $product = Product::create($data);

        if(isset($data['properties'])){
            $features = [];
            $count_features = 0;
            AttrValue::where('product_id',$product->id)->delete();
            foreach ($data['properties'] as $key => $value) {
                if($value){
                    if(count($value) > 3){
                        if($count_features < 10){
                            if(trim( $value[0])){
                                $features[] = trim( $value[0]);
                                $count_features++;
                            }

                        }
                        if($count_features < 10){
                            if(trim( $value[1])){
                                $features[] = trim( $value[1]);
                                $count_features++;
                            }
                        }
                        if($count_features < 10){
                            if(trim( $value[2])){
                                $features[] = trim( $value[2]);
                                $count_features++;
                            }
                        }
                    }else{
                        foreach ($value as $v){
                            if($count_features < 10){
                                if(trim($v)){
                                    $features[] = trim($v);
                                    $count_features++;
                                }

                            }
                        }
                    }
                    $value = implode(',',$value);
                    $attr_v = [
                        'product_id' => $product->id,
                        'attribute_id' => $key,
                    ];
                    if($value){
                        $a = AttrValue::firstOrCreate($attr_v);
                        $a->content = $value;
                        $a->save();
                    }

                }
            }
            if($features){
                $f = implode(',',$features);
                $product->features = $f;
                $product->save();

            }
        }

        if (isset($data['warning_id'])) {
            if ($data['warning_id']) {
                ProductMessage::create([
                    'gtin_code' => $product->gtin_code,
                    //'gln_code' => @$product->gln->gln,
                    'message_id' => $data['warning_id'],
                ]);
            }

        } else {
            ProductMessage::where('gtin_code', $product->gtin_code)->delete();
        }



        $m = PProduct::where('gtin_code', $product->gtin_code)->first();
        if (empty($m)) {
            $m = new PProduct();
            $m->gtin_code = $product->gtin_code;
            $m->internal_code = $product->internal_code;
            $m->save();
        }

        if (isset($data['images']) && count($data['images']) > 0) {
            foreach ($data['images'] as $image) {
                if ($image != $product->image_default) {

                    $m->push('attachments', [
                        'type' => 'image',
                        'link' => $image,
                    ]);
                }

            }

        }
        if (isset($data['videos']) && count($data['videos']) > 0) {
            foreach ($data['videos'] as $video) {
                $m->push('attachments', [
                    'type' => 'video',
                    'link' => $video,
                ]);

            }
        }

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

        $product->categories()->sync($data['categories']);
        $product->in_categories = implode(',',$data['categories']);
        $attrs = [];


        foreach ($request->input('attrs', []) as $attrId => $content) {

            if (trim($content)) {

                $attrs[$attrId] = ['content' => $content
                    , 'content_text' => strip_tags($content)
                    , 'short_content' => Str::words(strip_tags($content), 300, '')
                ];
            }

        }


        $product->attributes()->sync($attrs);

        $product->save();

        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Tạo sản phẩm ' . $product->product_name . '(' . $product->gtin_code . ')',
        ]);

        //Call api dong bo redis
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('PUT', env('DOMAIN_API') . 'products/' . $product->id, [
                'auth' => [env('USER_API'), env('PASS_API')],
                'timeout'         => 5
            ]);
            $res = json_decode((string)$res->getBody());

            if ($res->status != 200) return redirect()->route('Staff::Management::product2@index')
                ->with('danger', 'Thêm sản phẩm bị lỗi khi dong bo redis');
        } catch (RequestException $e) {
//            return $e->getResponse()->getBody();
        }

        return redirect()->route('Staff::Management::product2@index')
            ->with('success', 'Đã thêm Sản phẩm');
    }

    public function editByField(Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $product = Product::where('gtin_code', $request->input('gtin'))->firstOrFail();

        $a = [$product->id];
        $a = array_merge($a, $request->all());

        return redirect()->route('Staff::Management::product2@edit', $a);
    }

    public function edit($id, Request $request)
    {
        $_noheader = false;

        if ($request->input('_noheader') == 1) {
            $_noheader = true;
        }

        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $messages = Message::all();
        $product = Product::with(['attributes', 'categories'])->findOrFail($id);
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);
        $selectedCategories = $product->categories->lists('id')->toArray();
        $attributes = ProductAttr::all();
        $warning = ProductMessage::where('gtin_code', $product->gtin_code)->first();
        $distributors = Distributor::all();
        $distributorsData = collect([]);
        $reports = ProductReport::where('status', ICheckReport::STATUS_PENDING)->where('gtin_code', $product->gtin_code)->get();

        $distributorsData = DistributorProduct::where('product_id', $product->id)->get()->keyBy('distributor_id');

        $currencies = Currency::all();
        $rel = '';

        $m = PProduct::where('gtin_code', $product->gtin_code)->first();
        $images = [];
        $videos = [];
        if ($product->image_default) {
            $images[$product->image_default] = ['default' => true, 'prefix' => $product->image_default];
        }

        if ($m && isset($m->attachments)) {
            foreach ($m->attachments as $key => $image) {

                if ($image['type'] == 'image') {
                    $images[$key] = ['default' => false, 'prefix' => $image['link']];
                }
                if ($image['type'] == 'video') {
                    $videos[] = $image['link'];
                }

            }


        }

        return view('staff.management.product2.form', compact('videos', 'product', 'categories', 'selectedCategories', 'attributes', 'gln', 'messages', 'warning', 'currencies', 'reports', 'distributorsData', 'distributors', 'images', 'm', '_noheader'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }

        $product = Product::findOrFail($id);

        $this->validate($request, [
            'vendor' => 'exists:icheck_product.vendor,gln_code',
        ]);

        $data = $request->all();

        if ($data['keywords']) {
            $keywords = implode(',', $data['keywords']);
            $data['keywords'] = $keywords;
        }
        if (!isset($data['image_default'])) {
            if (isset($data['images']) and count($data['images']) > 0) {
                $data['image_default'] = $data['images'][0];
            } else {
                $data['image_default'] = '';
            }
        }


        $m = PProduct::where('gtin_code', $product->gtin_code)->first();
        if ($m) {
            $images = $m->attachments;
            $m->unset('attachments');

            if ($images != null) {
                foreach ($images as $key => $img) {
                    if (isset($images[$key]['type'])) {
                        if ($images[$key]['type'] == 'image') {
                            unset($images[$key]);
                        } elseif ($images[$key]['type'] == 'video') {
                            unset($images[$key]);
                        } else {
                            $m->push('attachments', $images[$key]);
                        }
                    }

                }
            }
            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    if ($image != $product->image_default) {
                        $m->push('attachments', [
                            'type' => 'image',
                            'link' => $image,
                        ]);
                    }

                }
            }
            if (isset($data['videos'])) {
                foreach ($data['videos'] as $video) {
                    $m->push('attachments', [
                        'type' => 'video',
                        'link' => $video,
                    ]);

                }
            }

        }

        if (isset($data['warning_id'])) {
            ProductMessage::where('gtin_code', $product->gtin_code)->delete();
            if ($data['warning_id']) {

                ProductMessage::create([
                    'gtin_code' => $product->gtin_code,
                    //'gln_code' => @$product->gln->gln,
                    'message_id' => $data['warning_id'],
                ]);
            }

        } else {
            ProductMessage::where('gtin_code', $product->gtin_code)->delete();
        }

        DistributorProduct::where('product_id', $product->id)->delete();
        $distributors = $request->input('distributors', []);

        foreach ($request->input('distributors_selected', []) as $distributorId) {
            $a = DistributorProduct::firstOrCreate([
                'product_id' => $product->id,
                'distributor_id' => $distributorId,
            ]);

            $distributor = [];

            if (isset($distributors[$distributorId])) {
                $distributor = $distributors[$distributorId];
                if (!isset($distributor['is_monopoly'])) {
                    $distributor['is_monopoly'] = 0;
                }
            } else {
                $distributor['is_monopoly'] = 0;
            }

            $a->update($distributor);
        }

        ProductReport::whereIn('id', $request->input('report_resolved', []))->update([
            'status' => ICheckReport::STATUS_RESOLVED,
        ]);


        $product->update($data);

        if(isset($data['properties'])){

            $features = [];
            $count_features = 0;
            AttrValue::where('product_id',$product->id)->delete();
            foreach ($data['properties'] as $key => $value) {
                if($value){
                    if(count($value) > 3){
                        if($count_features < 10){
                            if(trim( $value[0])){
                                $features[] = trim( $value[0]);
                                $count_features++;
                            }
                        }
                        if($count_features < 10){
                            if(trim( $value[1])){
                                $features[] = trim( $value[1]);
                                $count_features++;
                            }
                        }
                        if($count_features < 10){
                            if(trim( $value[2])){
                                $features[] = trim( $value[2]);
                                $count_features++;
                            }
                        }
                    }else{
                        foreach ($value as $v){
                            if($count_features < 10){
                                if(trim($v)){
                                    $features[] = trim($v);
                                    $count_features++;
                                }

                            }
                        }
                    }
                    $value = implode(',',$value);
                    $attr_value = [
                        'product_id' => $product->id,
                        'attribute_id' => $key,
                    ];
                    if($value){
                        $a = AttrValue::firstOrCreate($attr_value);
                        $a->content = $value;
                        $a->save();
                    }
                }
            }
            if($features){
                $f = implode(',',$features);
                $product->features = $f;
                $product->save();

            }
        }else{
            $product->features = null;
            $product->save();
        }

        if (isset($data['vendor'])) {
            $data['vendor'] = trim($data['vendor']);
            if ($vendor = Vendor::where('gln_code',  $data['vendor'] )->first()) {
                $product->vendor2()->associate($vendor);
            }
        }


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
        $product->categories()->sync($data['categories']);
        $product->in_categories = implode(',',$data['categories']);

        $attrs = [];

        foreach ($request->input('attrs', []) as $attrId => $content) {
            if ($content != '') {

                $c = str_replace(array("\n", "\r"), '', $content);

                $attrs[$attrId] = ['content' => $c
                    , 'content_text' => strip_tags($c)
                    , 'short_content' => Str::words(strip_tags($c), 300, '')
                ];
            }

        }

        $product->attributes()->sync($attrs);


        $product->save();

        ContributeProduct::where('gtin', $product->gtin_code)->whereIn('status', [ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->delete();

        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Sửa sản phẩm ' . $product->product_name . '(' . $product->gtin_code . ')',
        ]);
//
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('PUT', env('DOMAIN_API') . 'products/' . $product->id, [
                'auth' => [env('USER_API'), env('PASS_API')],
                'timeout'         => 5
            ]);
            $res = json_decode((string)$res->getBody());

            if ($res->status != 200) return redirect()->route('Staff::Management::product2@index')
                ->with('danger', 'cập nhật thông tin Sản phẩm bi loi khi dong bo redis');
        } catch (\Exception $e) {
        }

        if ($request->ajax() || $request->wantsJson()) {
            return 'ok';
        } else {
            return redirect()->route('Staff::Management::product2@index')
                ->with('success', 'Đã cập nhật thông tin Sản phẩm');
        }
    }

    public function analytics($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);
        $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();
        $attributes = ProductAttr::all();
        $gln = Auth::user()->gln()->where('status', GLN::STATUS_APPROVED)->get();

        return view('staff.management.product.form', compact('product', 'categories', 'selectedCategories', 'attributes', 'gln'));
    }

    public function approve($id, Request $request)
    {
        $product = Product::findOrFail($id);

        $data = [
            'name' => $product->name,
            'gtin_code' => $product->barcode,
            'image' => [
                [
                    'url' => $product->image,
                    'default' => true,
                ]
            ],
            'price' => $product->price,
            'gln_code' => $product->gln->gln,
            'categories' => ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray(),
            'attributes' => [],
        ];

        foreach ($product->attrs as $attr => $value) {
            $data['attributes'][] = ['attribute' => $attr, 'content' => $value];
        }

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request(
                'PUT',
                config('remote.server') . '/web/products/' . $product->barcode,
                [
                    'json' => $data,
                ]
            );
            $res = $res->getBody();
            $product->status = Product::STATUS_APPROVED;
            $product->save();
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }

        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Chấp nhận sản phẩm ' . $product->name . '(' . $product->barcode . ')',
        ]);


        return redirect()->back()
            ->with('success', 'Sản phẩm ' . $product->name . ' đã được chấp nhận');
    }

    public function ignoreByUser($gtin, Request $request)
    {


        $contribute = Contribute::find($gtin);
        $contribute->status = Contribute::STATUS_DISAPPROVED;
        $contribute->save();

        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Không Chấp nhận sản phẩm Contribute' . $contribute->product_name . '(' . $contribute->gtin_code . ')',
        ]);


        return redirect()->back()
            ->with('success', 'Sản phẩm ' . $contribute->product_name . ' đã được bo qua');
    }

    public function approveByUser($gtin, Request $request)
    {

        $gln_code = $request->input('gln_code');
        $vendor_id = 0;
        if ($gln_code) {

            $vendor = Vendor::where('gln_code', $gln_code)->first();
            if (empty($vendor)) {
                return redirect()->back()
                    ->with('success', 'Có lỗi vì không tồn tại gln_code thuộc hệ thống');
            } else {
                $vendor_id = $vendor->id;
            }
        }

        $contribute = Contribute::find($gtin);

        $product = Product::where('gtin_code', $contribute->gtin_code)->first();
        if (empty($product)) {
            $product = new Product();
        }

        if ($contribute->product_name != '') {
            $product->product_name = $contribute->product_name;
        }
        if ($contribute->price > 0) {
            $product->price_default = $contribute->price;
        }
        if ($vendor_id != 0) {
            $product->vendor_id = $vendor_id;
        }
        $product->gtin_code = $contribute->gtin_code;

        $images = array();
        $data_images = $contribute->attachments;
        $data_images = json_decode($data_images);
        foreach ($data_images as $img) {
            if ($img->type == 'image') {
                $images[] = $img->link;
            }
        }

        if (count($images) > 0) {

            $pproduct = PProduct::where('gtin_code', $contribute->gtin_code)->first();

            if (empty($pproduct)) {

                $pproduct = new PProduct();
                $pproduct->gtin_code = $product->gtin_code;
                $pproduct->internal_code = $product->internal_code;
                $pproduct->save();

                foreach ($images as $image) {
                    if ($image != $product->image_default) {
                        $pproduct->push('attachments', [
                            'type' => 'image',
                            'link' => $image,
                            'owner_id' => $contribute->icheck_id
                        ]);
                    }

                }

            } else {

                $pimages = $pproduct->attachments;

                if ($product->image_default == '') {
                    $product->image_default = $images[0];
                }

                foreach ($images as $image) {
                    if ($image != $product->image_default) {
                        $pproduct->push('attachments', [
                            'type' => 'image',
                            'link' => $image,
                            'owner_id' => $contribute->icheck_id
                        ]);
                    }

                }

                $pproduct->save();
            }

        } else {
            $pproduct = new PProduct();
            $pproduct->gtin_code = $product->gtin_code;
            $pproduct->internal_code = $product->internal_code;
            $pproduct->save();
        }
        if($contribute->categories){
            $categories = json_decode($contribute->categories,true);
            if($categories and is_array($categories)){
                $product->categories()->sync($categories);
                $product->in_categories = implode(',',$categories);
            }
        }
        if(json_decode($contribute->properties,true)){
            $count_features = 0;
            $features = [];
            AttrValue::where('product_id',$product->id)->delete();
            $properties = json_decode($contribute->properties,true);
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
        $contribute->status = Contribute::STATUS_APPROVED;
        $contribute->save();

        $product->save();
        $contribute_product = Contribute::where('gtin_code', $contribute->gtin_code)->where('id', '<>', $gtin)->update(['status' => Contribute::STATUS_DISAPPROVED]);

        //delete gtin_code in contribute by ctv
        ContributeProduct::whereIn('status',[ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->where('gtin', $product->gtin_code)->delete();


        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Approve sản phẩm Contribute ' . $product->product_name . '(' . $product->gtin_code . ')',
        ]);

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('PUT', env('DOMAIN_API') . 'products/' . $product->id, [
                'auth' => [env('USER_API'), env('PASS_API')],
            ]);
            $res = json_decode((string)$res->getBody());

            if ($res->status != 200) return redirect()->back()
                ->with('danger', 'Sản phẩm' . $product->name . ' đã được chấp nhận!! Nhưng đồng bộ redis thất bại');
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }
        return redirect()->back()
            ->with('success', 'Sản phẩm ' . $product->name . ' đã được chấp nhận');
    }

    public function removeByUser($gtin, Request $request)
    {
        $p = MProduct::where('gtin_code', $gtin)->first();
        $p->owner = "";
        $p->name_user = '';
        $p->image_user = [];
        $p->time_user_update = null;
        $p->save();

        return 'ok';
    }

    public function listProductWarning(Request $request)
    {

          if (auth()->guard('staff')->user()->cannot('products2-w')) {
              abort(403);
          }
        $messages = Message::all();
        $warnings = new ProductMessage;

        if ($request->input('type')) {
            $warnings = $warnings->where('message_id', $request->input('type'));
        }

        if ($request->input('search')) {
            $products = Product::select(['gtin_code'])->where(function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('gtin_code', 'like', '%' . $request->input('search') . '%');
            })->get();
            $warnings = $warnings->whereIn('gtin_code', $products->lists('gtin_code')->toArray());
        }

        $warnings = $warnings->paginate(10);

        return view('staff.management.product2.list_warning', compact('messages', 'warnings'));
    }

    public function listProductByUser(Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('products2-u')) {
            abort(403);
        }

        $products = Contribute::orderBy('createdAt', 'desc')->where('status', Contribute::STATUS_PENDING_APPROVAL);
        if ($request->input('name')) {
            $key = $request->input('name');
            $products = $products->where('icheck_id', 'like', '%' . $key . '%');
        }
        if ($request->input('date')) {
            $date = $request->input('date');
            $date = str_replace('/', '-', $date);
            try {
                $date = Carbon::createFromFormat('d-m-Y', $date)->startOfDay();
                $products = $products->whereDate('createdAt', '=', $date);
            } catch (\Exception $ex) {

            }
        }
        if ($request->input('search')) {
            $products = $products->where(function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('gtin_code', 'like', '%' . $request->input('search') . '%');
            });
        }
        $products = $products->paginate(50);
        foreach ($products as $product){
            $catPro = [];
            if(json_decode($product->categories) and is_array(json_decode($product->categories))){
                $catPro = Category::whereIn('id',json_decode($product->categories))->get();

            }else{
                $productS = $product->product;
                $categories = $productS->categories;
                if($categories){
                    $catPro = $categories;
                }
                $categories = $categories->lists('id')->toArray();
                $product->categories = json_encode($categories);

                $properties2 = $productS->properties()->get();
                $properties = null;
                if($properties2){
                    foreach ($properties2 as $property){
                        if($property->content){
                            $v = explode(',',$property->content);
                            $properties[$property->attribute_id] = $v;
                        }
                    }
                    $properties = json_encode($properties);
                    $product->properties = $properties;
                }
                $product->save();
            }


            $product->renderProperties='';
            if(json_decode($product->properties,true)){
                $product->renderProperties = static::renderPropertiesContribute($catPro,$product);
            }


        }
        $categories = Category::where('parent_id',12)->get();
        $categories = $this->getAllCategories($categories);
        return view('staff.management.product2.list_by_user', compact('products', 'categories'));
    }

    public function moonCake($id, Request $request)
    {
        $product = MProduct::findOrFail($id);
        $acc = Account::where('account', $product->owner)->firstOrFail();
        $jwt = JWT::encode(['device_id' => $product->device_user_update], 'icheck2future');

        $client = new \GuzzleHttp\Client();

        $data = [
            'icheck_id' => $acc->icheck_id,
            'hook' => 'review',
            'params' => [
                'is_moon_cake' => '1',
            ],
        ];

        try {
            $res = $client->request(
                'POST',
                'http://10.5.11.31:27896/tr',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $jwt
                    ],
                    'json' => $data,
                ]
            );
            $res = $res->getBody();

            $noti = new MNotification();
            $noti->message = 'Chúc mừng Bạn đã nhận được một lượt quay siêu tốc';
            $noti->icon_type = 'group';
            $noti->icon = '-TheHulk/f03acc6e6c/1473819566_aae876ed2acd4d8f8feb5d10263a4f62_052a8c';
            $noti->action = 'update';
            $noti->refer_type = 'minigame';
            $noti->refer = '';
            $noti->to = [[
                'icheck_id' => $acc->icheck_id,
                'status' => 0,
            ]];
            $noti->save();

            $config = MConfig::first();

            $config->push('data_event', $product->gtin_code, true);


            return $res;
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }
    }

    public function listProductByAgency($agencyId, Request $request)
    {
        $agency = Agency::findOrFail($agencyId);
        $products = $agency->products()->orderBy('createdAt', 'desc');

        if ($request->input('search')) {
            $products = $products->where(function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('gtin_code', 'like', '%' . $request->input('search') . '%');
            });
        }

        $products = $products->paginate(10);

        return view('staff.management.product2.list_by_agency', compact('agency', 'products'));
    }

    public function searchProductByDistributor(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('products2-d')) {
            abort(403);
        }
        $distributors = Distributor::all();

        $products = false;
        if ($request->input('distributor')) {
            $distributor = Distributor::findOrFail($request->input('distributor'));
            $products = $distributor->products()->orderBy('createdAt', 'desc');

            if ($request->input('search')) {
                $products = $products->where(function ($query) use ($request) {
                    $query->where('product_name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('gtin_code', 'like', '%' . $request->input('search') . '%');
                });
            }

            $products = $products->paginate(10);
        }


        return view('staff.management.product2.search_by_distributor', compact('distributors', 'products'));
    }

    public function listProductByDistributor($distributorId, Request $request)
    {

        $distributor = Distributor::findOrFail($distributorId);
        $products = $distributor->products()->orderBy('createdAt', 'desc');

        if ($request->input('search')) {
            $products = $products->where(function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->input('search') . '%')
                    ->orWhere('gtin_code', 'like', '%' . $request->input('search') . '%');
            });
        }

        $products = $products->paginate(10);

        return view('staff.management.product2.list_by_distributor', compact('distributor', 'products'));
    }

    public function inline($id, Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }
        $this->validate($request, [
            'vendor' => 'exists:icheck_product.vendor,gln_code',
        ]);
        $product = Product::find($id);

        if ($request->input('product_name')) {
            if ($request->input('product_name') == 'dell-all-1994') {
                $product->product_name = '';
            } else {
                $product->product_name = $request->input('product_name');
            }

        }
        if ($request->input('price_default') != null) {
            $price = $request->input('price_default');
            if($price == 0 ){
                $product->currency_default = 1;
            }
            $product->price_default = $price;
        }
        if ($request->input('attrs')) {


            $attrs = $request->input('attrs');
            $content = $attrs[1];

            if (trim($content)) {
                $attrs[1] = ['content' => $content
                    , 'content_text' => strip_tags($content)
                    , 'short_content' => Str::words(strip_tags($content), 300, '')
                ];
                $count = ProductInfo::where('attribute_id', 1)->where('product_id', $product->id)->count();
                if ($count > 0) {
                    $product->attributes()->updateExistingPivot(1, $attrs[1]);
                } else {
                    $info = new ProductInfo;
                    $info->product_id = $product->id;
                    $info->attribute_id = 1;
                    $info->content = $content;
                    $info->content_text = strip_tags($content);
                    $info->short_content = Str::words(strip_tags($content), 300, '');
                    $info->save();
                }

            } else {
                $product->attributes()->detach(1);
            }


        }

        if ($request->input('images')) {

            $data = $request->input('images');
            if ($data != 'del-all') {

                $product->image_default = $data[0];
                $image_default = $data[0];
                $m = PProduct::where('gtin_code', $product->gtin_code)->first();


                if ($m) {

                    $images = $m->attachments;
                    $m->unset('attachments');
                    if ($images != null) {
                        foreach ($images as $key => $img) {
                            if (isset($images[$key]['type'])) {
                                if ($images[$key]['type'] == 'image') {
                                    unset($images[$key]);
                                } else {
                                    $m->push('attachments', $images[$key]);
                                }
                            }

                        }
                    }

                    foreach ($data as $image) {
                        if ($image != $product->image_default) {
                            $m->push('attachments', [
                                'type' => 'image',
                                'link' => $image,
                            ]);
                        }

                    }


                } else {

                    $pproduct = new PProduct();
                    $pproduct->gtin_code = $product->gtin_code;
                    $pproduct->internal_code = $product->internal_code;
                    $pproduct->save();

                    foreach ($data as $image) {
                        if ($image != $product->image_default) {
                            $pproduct->push('attachments', [
                                'type' => 'image',
                                'link' => $image,
                            ]);
                        }

                    }
                }

            } else {

                $m = PProduct::where('gtin_code', $product->gtin_code)->first();
                if ($m) {

                    $images = $m->attachments;
                    $m->unset('attachments');
                    if ($images != null) {
                        foreach ($images as $key => $img) {
                            if (isset($images[$key]['type'])) {
                                if ($images[$key]['type'] == 'image') {
                                    unset($images[$key]);
                                } else {
                                    $m->push('attachments', $images[$key]);
                                }
                            }

                        }
                    }


                }
                $product->image_default = '';
            }
        }
        if($request->has('categories')){
            $categories = $request->input('categories');
            if(is_array($categories)){
                    $result  = [];
                    foreach ($categories as $id){
                        $category = Category::find(intval($id));
                        if($category){
                            $r = $this->getParent($category,[]);
                            $r[] = intval($id);
                            $result = array_unique( array_merge( $result , $r ) );
                        }
                    }
                $categories = $result;
                $product->categories()->sync($categories);
                $product->in_categories = implode(',',$categories);
            }
            if($categories == 'del-all'){
                $product->categories()->sync([]);
                $product->in_categories = null;
            }
        }
        $product->save();
        Contribute::where('gtin_code', $product->gtin_code)->update(['status' => Contribute::STATUS_DISAPPROVED]);
        ContributeProduct::whereIn('status',[ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,ContributeProduct::STATUS_PENDING_APPROVAL])->where('gtin', $product->gtin_code)->delete();
        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Sửa sản phẩm ' . $product->product_name . '(' . $product->gtin_code . ')',
        ]);

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('PUT', env('DOMAIN_API') . 'products/' . $product->id, [
                'auth' => [env('USER_API'), env('PASS_API')],
            ]);
            $res = json_decode((string)$res->getBody());

            if ($res->status != 200) return 'error';
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }

        return 'oke';
    }

    public function contributeInline($id, Request $request)
    {

        $contribute = Contribute::find($id);

        if ($request->input('product_name')) {
            $contribute->product_name = $request->input('product_name');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa sản phẩm Contribute ' . $contribute->product_name . '(' . $contribute->gtin_code . ')',
            ]);
        }
        if ($request->input('price') != null) {
            $contribute->price = $request->input('price');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa sản phẩm Contribute ' . $contribute->product_name . '(' . $contribute->gtin_code . ')',
            ]);
        }
        if ($request->input('images')) {
            $data_images = $request->input('images');

            $images = json_decode($contribute->attachments, true);


            if ($data_images != 'del-all') {


                foreach ($images as $key => $img) {
                    if (isset($images[$key]['type'])) {
                        if ($images[$key]['type'] == 'image') {
                            unset($images[$key]);
                        }
                    }

                }
                foreach ($data_images as $image) {

                    $images[] = [
                        'type' => 'image',
                        'link' => $image
                    ];


                }


                $contribute->attachments = json_encode(array_values($images));
            } else {

                foreach ($images as $key => $img) {
                    if (isset($images[$key]['type'])) {
                        if ($images[$key]['type'] == 'image') {
                            unset($images[$key]);
                        }
                    }
                }
                $contribute->attachments = json_encode(array_values($images));

            }
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa sản phẩm Contribute ' . $contribute->product_name . '(' . $contribute->gtin_code . ')',
            ]);
        }
        if ($request->input('categories')) {
            $categories = $request->input('categories');

            $categories = json_decode($categories);
            if($categories){

                $result  = [];

                foreach ($categories as $id){
                    $category = Category::find($id);
                    $r = $this->getParent($category,[]);

                    $r[] = intval($id);
                    $result = array_unique( array_merge( $result , $r ) );
                }
                $categories = $result;

            }
            if (empty($categories)) {
                $categories = array();
            }
            $contribute->categories = json_encode($categories);

            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa sản phẩm Contribute ' . $contribute->product_name . '(' . $contribute->gtin_code . ')',
            ]);
        }

        if ($request->input('attrs')) {

            $attrs = $request->input('attrs');
            $content = $attrs[1];

            if (trim($content)) {
                $attrs[1] = ['content' => $content
                    , 'content_text' => strip_tags($content)
                    , 'short_content' => Str::words(strip_tags($content), 300, '')
                ];
                $count = ProductInfo::where('attribute_id', 1)->where('product_id', $contribute->product->id)->count();
                if ($count > 0) {
                    $contribute->attributes()->updateExistingPivot(1, $attrs[1]);
                } else {
                    $info = new ProductInfo;
                    $info->product_id = $contribute->product->id;
                    $info->attribute_id = 1;
                    $info->content = $content;
                    $info->content_text = strip_tags($content);
                    $info->short_content = Str::words(strip_tags($content), 300, '');
                    $info->save();
                }

            } else {
                $contribute->attributes()->detach(1);
            }
        }
        $contribute->save();

        return 'oke';
    }

    public function activeWarning(Request $request)
    {
        $data = $request->all();
        if ($data['type'] == 'add') {
            $message_id = $data['message_id'];
            $gtin_code = $data['gtin'];
            $gtin_code = preg_split("/\\r\\n|\\r|\\n/", $gtin_code);

            foreach ($gtin_code as $gtin) {
                $pM = new ProductMessage();
                $pM->gtin_code = $gtin;
                $pM->message_id = $message_id;
                $pM->save();
            }
            return redirect()->back()
                ->with('success', 'Thêm cảnh báo thành công');
        }
        if ($data['type'] == 'delete') {
            $gtin_code = $data['gtin'];
            $gtin_code = preg_split("/\\r\\n|\\r|\\n/", $gtin_code);
            ProductMessage::whereIn('gtin_code', $gtin_code)->delete();
            return redirect()->back()
                ->with('success', 'Xóa cảnh báo thành công');
        }
    }

    public function approveListByUser(Request $request)
    {
        if ($request->input('selected')) {
            $email = auth()->guard('staff')->user()->email;
            $ids = $request->input('selected');
            $this->dispatch(new ApproveListContributeUserJob($ids,$email));

            return redirect()->back()
                ->with('success', 'Sản phẩm đã được thêm vào queue                                      ');
        }
        return redirect()->back()
            ->with('success', 'Vui lòng chọn sản phẩm       ');
    }

    public function ignoreListByUser(Request $request)
    {
        if ($request->input('selected')) {

            $ids = $request->input('selected');
            foreach ($ids as $id) {
                $contribute = Contribute::find($id);
                $contribute->status = Contribute::STATUS_DISAPPROVED;
                $contribute->save();

                \App\Models\Enterprise\MLog::create([
                    'email' => auth()->guard('staff')->user()->email,
                    'action' => 'Không Chấp nhận sản phẩm Contribute' . $contribute->product_name . '(' . $contribute->gtin_code . ')',
                ]);

            }
            return redirect()->back()
                ->with('success', 'Sản phẩm đã được bo qua');
        }
        return redirect()->back()
            ->with('success', 'Vui lòng chọn sản phẩm ');

    }

    public function removeFieldForm(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('products2-removeField')) {
            abort(403);
        }
        $fields = [
            'name' => 'Tên sản phẩm',
            'price' => 'Giá',
            'image' => 'Ảnh sản phẩm',
            'category' => 'Danh mục',
            'ttsp' => 'Thông tin sản phẩm',
            'cccn' => 'Chứng chỉ và chứng nhận',
            'ttct' => 'Thông tin công ty',
            'pbtg' => 'Phân biệt thật giả'
        ];
        return view('staff.management.product2.remove_field_product', compact('fields'));
    }

    public function removeField(Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('edit-product')) {
            abort(403);
        }
        if ($request->input('gtin') && $request->input('fields')) {

            $gtin = $request->input('gtin');
            $fields = $request->input('fields');

            $this->dispatch(new RemoveFieldProductJob($gtin, $fields));

            return redirect()->back()
                ->with('success', 'Sản phẩm đã được thêm vào queue                                      ');
        }

        return redirect()->back()
            ->with('success', 'Vui lòng nhập list gtin và chọn field');
    }

    public function delete(Request $request,$gtin){

        if (auth()->guard('staff')->user()->cannot('delete-product')) {
            abort(403);
        }
        Product::where('gtin_code',$gtin)->delete();
        ProductDN::where('barcode',$gtin)->delete();
        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Xóa mã sản phẩm : '.$gtin
        ]);
        return redirect()->route('Staff::Management::product2@index')->with('success','Xóa thành công');
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
    public static function getCategoriesNotSub(){
        $categories = Category::all();
        $array = [];
        foreach ($categories as $category){
            $subCategories = Category::where('parent_id', '=', $category->id)->count();
            if($subCategories <= 0){
                    $array[] =  $category->id;
            }
        }
        return $array;
    }
    public function reportHCM(){
        $this->dispatch(new ReportData(auth('staff')->user()->email));
        return 'oke';
    }

    public static function renderPropertiesContribute($categories, $product)  {
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
                    $result .= static::templatePropertiesContribute($property, $count, $value,$product->id);
                }
            }
        }
        return $result;
    }
    private static function templatePropertiesContribute($attr, $count = 1, $properties,$productId){
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
