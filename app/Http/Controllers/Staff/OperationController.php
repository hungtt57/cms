<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Notification;
use Carbon\Carbon;
use App\Models\Social\User;
use App\Models\Enterprise\ProductDone;
//use App\Models\Social\Product as SocialProduct;
use App\Models\Icheck\Product\Product as SocialProduct;
//use App\Models\Social\Category;
use App\Models\Icheck\Product\Category;
use Illuminate\Support\Facades\Cache;
use App\Models\Enterprise\ProductCategory;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
//use App\Models\Social\CategoriesProduct;
use App\Models\Icheck\Product\CategoryProduct;
class OperationController extends Controller
{
    
    public function index(Request $request)
    {
        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

        return view('staff.operation.index', compact('startDate', 'endDate'));
    }
    
    public function add(Request $request)
    {
        $gtins = null;
        if ($request->has('gtin')) {
            $gtinInput = explode(",", $request->input('gtin'));
            $gtins = SocialProduct::select(['gtin_code','product_name','image_default','id'])->whereIn('gtin_code', $gtinInput)->get()->keyBy('id')->toArray();
            $catygories = CategoryProduct::select(['category_id'])->whereIn('product_id',array_keys($gtins))->get()->keyBy('category_id')->toArray();
            $gtinReady = ProductDone::select(['gtin'])->whereIn('gtin',$gtinInput)->get()->keyBy('gtin')->toArray();
        }
        if($gtins) {
            DB::beginTransaction();
            try {
                foreach ($gtins as $j) {
                    if(!isset($gtinReady[$j['gtin_code']]) || empty($gtinReady)) {
                        // Lưu thông tin sản phẩm
                        $productDone = new ProductDone();
                        $productDone->gtin = $j['gtin_code'];
                        $productDone->product_name = $j['product_name'];
                        $productDone->product_image = $j['image_default'];
                        $productDone->status = ProductDone::STATUS_NEED;
                        $productDone->save();
                        // Lưu Categories
                        if($catygories) {
                            foreach ($catygories as $c) {
                                $cateSave = new ProductCategory();
                                $cateSave->category_id = $c;
                                $cateSave->product_id = $productDone->id;
                                $cateSave->save();
                            }
                        }
                    }
                }
            } catch(\Exception $e){
                DB::rollback();
                throw $e;
            }
            DB::commit();     
            return redirect()->route('Staff::operation@asy')
            ->with('success', 'Đã thêm sản phẩm');
        }
        return view('staff.operation.add', compact('gtin'));
    }
    
    public function time(Request $request)
    {
        $notification = null;
        return view('staff.operation.time', compact('notifications'));
    }
    
    public function create(Request $request)
    {
        $notification = new Notification();
        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        } else {
            $dateRange = explode('-', $request->input('date_range'));
            $startDate = isset($dateRange[0]) ? $dateRange[0] : 0;
            $endDate = isset($dateRange[1]) ? $dateRange[1] : 0;
        }
        if($request->method() == 'POST') {
            $this->validate($request, [
                'name' => 'required',
                'image_video' => 'required'
            ]);
            if ($request->hasFile('image_video')) {
                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request(
                        'POST',
                        'http://upload.icheck.vn/v1/images?uploadType=simple',
                        [
                            'body' => file_get_contents($request->file('image_video')),
                        ]
                    );
                    $res = json_decode((string) $res->getBody());
                } catch (RequestException $e) {
                    return $e->getResponse()->getBody();
                }
                $notification->image_video = $res->prefix;
            }
            $notification->name = $request->input('name');
            $notification->content = $request->input('content');
            $notification->cate = $request->input('cate');
            $notification->obj = $request->input('obj');
            $notification->date_start = strtotime(str_replace('/', '-', $startDate));
            $notification->date_stop = strtotime(str_replace('/', '-', $endDate));
            $notification->status = $request->input('status');
            if($notification->save()) {
                return redirect()->route('Staff::operation@view')
            ->with('success', 'Tạo mới Notification thành công.');
            }
        }
        return view('staff.operation.create', compact('notification','startDate', 'endDate'));
    }
    
    public function view(Request $request)
    {
        $notification = null;
        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }
        return view('staff.operation.view', compact('notification','startDate', 'endDate'));
    }
    
    public function asy(Request $request)
    {        
        $products = new ProductDone();
        
        if ($createdAtFrom = $request->input('created_at_from')) {
            $products = $products->where('created_at', '>=', Carbon::createFromFormat('Y-m-d', $createdAtFrom)->startOfDay());
        }

        if ($createdAtTo = $request->input('created_at_to')) {
            $products = $products->where('created_at', '<=', Carbon::createFromFormat('Y-m-d', $createdAtTo)->endOfDay());
        }

        $count0 = clone $products;
        $count0 = $count0->where('status', ProductDone::STATUS_DISAPPROVED)->count();

        $count1 = clone $products;
        $count1 = $count1->where('status', ProductDone::STATUS_APPROVED)->count();
        
        $count2 = clone $products;
        $count2 = $count2->where('status', ProductDone::STATUS_PENDING_APPROVAL)->count();
        
        $count3 = clone $products;
        $count3 = $count3->where('status', ProductDone::STATUS_IN_PROGRESS)->count();
        
        $count4 = clone $products;
        $count4 = $count4->where('status', ProductDone::STATUS_ERROR)->count();
        
        $count5 = clone $products;
        $count5 = $count5->where('status', ProductDone::STATUS_NEED)->count();
        
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);
        if($categories) {
            foreach ($categories as $k) {
                $cateAll[$k->id] = $k->name;
            }
        }
        if ($request->has('status') and ($status = $request->input('status')) !== '') {
            $products = $products->where('status', $status)->paginate(20);
        } else {
            $products = $products->paginate(20);
        }
        return view('staff.operation.asy', compact('products', 'count0', 'count1','count2', 'count3','count4','count5'));
    }
    
    public function find(Request $request)
    {
        $notification = null;
        return view('staff.operation.find', compact('notification'));
    }
    
    public function edit($id)
    {
        
        $product = ProductDone::findOrFail($id);
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);
        $data = \App\Models\Enterprise\ProductCategory::
                where('product_id',$product->id)
                ->get()
                ->keyBy('category_id')
                ->toArray();
        $dataCatygories = array_keys($data);
        $img = ProductDone::get_image_url($product->product_image);
        return view('staff.operation.edit', compact('product', 'categories','img', 'dataCatygories'));
    }
    
    public function update($id, Request $request)
    {
        $product = ProductDone::findOrFail($id);
        $data = $request->all();
        DB::beginTransaction();
        try {
            if($request->has('productCategory')) {
                $product_catgories = $request->input('productCategory');
                $deletedRows = \App\Models\Enterprise\ProductCategory::
                        where('product_id', $product->id)
                        ->delete();
                foreach ( $product_catgories as $s => $k) {
                    $pCatygories = new \App\Models\Enterprise\ProductCategory();
                    $pCatygories->category_id = $s;
                    $pCatygories->product_id =$product->id;
                    $pCatygories->save();
                }
            }
            if ($request->hasFile('product_image')) {
                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request(
                        'POST',
                        'http://upload.icheck.vn/v1/images?uploadType=simple',
                        [
                            'body' => file_get_contents($request->file('product_image')),
                        ]
                    );
                    $res = json_decode((string) $res->getBody());
                } catch (RequestException $e) {
                    return $e->getResponse()->getBody();
                }
                $data['product_image'] = $res->prefix;
            }
            $product->update($data);
        } catch (ValidationException $e){
            DB::rollback();
            return Redirect::to('/form')
                ->withErrors( $e->getErrors() )
                ->withInput();
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return redirect()->route('Staff::operation@edit', $product->id)
            ->with('success', 'Đã cập nhật thông tin sản phẩm');
    }
    
    public function delete($id, Request $request)
    {
        $product = ProductDone::findOrFail($id);
        $product->delete();

        return redirect()->route('Staff::operation@asy')
            ->with('success', 'Đã xoá sản phẩm');
    }
    
    public function batchDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        ProductDone::destroy($ids);
        return redirect()->back()
            ->with('success', 'Đã xoá Sản phẩm');
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
   
    public function product(Request $request)
    {
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);
        return view('staff.operation.product', compact('categories'));
    }
    
    public function barcode(Request $request)
    {    
        if($data = $request->input('gtin')) {
            $Gtins = explode(',', $data);
            $gtin = ProductDone::select(['gtin'])->whereIn('gtin',$Gtins)->get()->keyBy('gtin')->toArray();
            foreach ($Gtins as $Gtin) {
                $productDone = new ProductDone();
                if(!isset($gtin[$Gtin])) {
                    $dataCrawler = $this->curl("http://xnk.vn/product/find-upc?upc={$Gtin}");
                    $productDone->status = ProductDone::STATUS_NEED;
                    if($dataCrawler) {
                        $s = (array) \GuzzleHttp\json_decode($dataCrawler);
                        $productDone->product_name = isset($s['name']) ? $s['name'] : '';
                        $productDone->product_price = isset($s['price']) ? $s['name'] : '';
                        if(isset($s['price'])) {
                            $client = new \GuzzleHttp\Client();
                            try {
                                $res = $client->request(
                                    'POST',
                                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                                    [
                                        'body' => file_get_contents($s['images']),
                                    ]
                                );
                                $res = json_decode((string) $res->getBody());
                            } catch (RequestException $e) {
                                return $e->getResponse()->getBody();
                            }
                            $productDone->product_image = $res->prefix;
                        }
                    }
                    $productDone->gtin = $Gtin;
                    $productDone->save();
                }
            }
            return redirect()->route('Staff::operation@asy')
            ->with('success', 'Đã cập nhật Gtin thành công.');
        }
    }
    
    public function notifies(Request $request)
    {
        $notification = new Notification();
        if ($createdAtFrom = $request->input('created_at_from')) {
            $notification = $notification->where('created_at', '>=', Carbon::createFromFormat('Y-m-d', $createdAtFrom)->startOfDay());
        }

        if ($createdAtTo = $request->input('created_at_to')) {
            $notification = $notification->where('created_at', '<=', Carbon::createFromFormat('Y-m-d', $createdAtTo)->endOfDay());
        }

        $count0 = clone $notification;
        $count0 = $count0->where('status', Notification::IMAGE)->count();

        $count1 = clone $notification;
        $count1 = $count1->where('status', Notification::VIDEO)->count();

        $notification = Notification::orderBy('created_at', 'desc')->paginate(20);
        return view('staff.operation.notifies', compact('notification', 'count0', 'count1'));
    }
    
    public function editNotifies(Request $request, $id)
    {
        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        } else {
            $dateRange = explode('-', $request->input('date_range'));
            $startDate = isset($dateRange[0]) ? $dateRange[0] : 0;
            $endDate = isset($dateRange[1]) ? $dateRange[1] : 0;
        }
        $notifies = Notification::findOrFail($id);
        if($request->method() == "POST") {
            $data = $request->all();
            if ($request->hasFile('image_video')) {
                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request(
                        'POST',
                        'http://upload.icheck.vn/v1/images?uploadType=simple',
                        [
                            'body' => file_get_contents($request->file('image_video')),
                        ]
                    );
                    $res = json_decode((string) $res->getBody());
                } catch (RequestException $e) {
                    return $e->getResponse()->getBody();
                }
                $data['image_video'] = $res->prefix;
            }
            $notifies->update($data);
            return redirect()->route('Staff::operation@editNotifies', $notifies->id)
            ->with('success', 'Đã cập nhật Notification thành công.');
        }
        return view('staff.operation.edit-notifies', compact('notifies','startDate', 'endDate'));
    }
    
    public function deleteNotifies($id, Request $request)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('Staff::operation@notifies')
            ->with('success', 'Đã xoá Notification');
    }

    private function curl($url)
    {
        try {
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
            $result = curl_exec($curl_handle);
            if (curl_errno($curl_handle) > 0) {
                return null;
            }
            curl_close($curl_handle);
            return $result;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    public function accept(Request $request, $id)
    {   
        DB::beginTransaction();
        try {
            // Cập nhập trạng thái
            $product = ProductDone::findOrFail($id);
            $product->status = ProductDone::STATUS_APPROVED;
            $product->update($request->all());
            // Đồng bộ thông tin sản phẩm
            $socialProduct = SocialProduct::where('gtin_code',$product->gtin)->get()->all();
            $p = $socialProduct ? $socialProduct[0] : new SocialProduct();
            $p->product_name = $product->product_name;
            $p->image_default = $product->product_image;
            $p->price_default = $product->product_price;
            $p->gtin_code = $product->gtin;
            $p->internal_code = isset($p->internal_code) ? $p->internal_code : 'ip_'.$product->gtin;
            $p->vendor = isset($p->vendor) ? $p->vendor : 0;
            $p->save();
            // Cập nhật categories
            $cate = array_keys(ProductCategory::
                    where('product_id', $product->id)
                    ->get()
                    ->keyBy('category_id')
                    ->ToArray());
            if($cate) {
                $categoriesProduct = CategoryProduct::
                where('product_id', $product->id)
                ->delete();
                foreach ($cate as $k) {
                    $pCatygories = new CategoryProduct();
                    $pCatygories->category_id = $k;
                    $pCatygories->product_id =$product->id;
                    $pCatygories->save();
                }
            }
            
            // Cộng tiền cho Cộng tác viên
            $collaborators = \App\Models\Enterprise\Collaborator::findOrFail($product->user_id);
            if(!$collaborators) {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Không tồn tại Công tác viên');
            }
            $collaborators->balance +=  $collaborators->balance + $product->price;
            $collaborators->save();
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        DB::commit();       
        return redirect()->route('Staff::operation@asy')
            ->with('success', 'Cập nhật thành công');
    }
    
    public function accepts(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $product = ProductDone::findOrFail($id);
                $product->status = ProductDone::STATUS_APPROVED;
                $product->save();
                // Đồng bộ thông tin sản phẩm
                $socialProduct = SocialProduct::where('gtin_code',$product->gtin)->get()->all();
                $p = $socialProduct ? $socialProduct[0] : new SocialProduct();
                $p->product_name = $product->product_name;
                $p->image_default = $product->product_image;
                $p->price_default = $product->product_price;
                $p->gtin_code = $product->gtin;
                $p->internal_code = isset($p->internal_code) ? $p->internal_code : 'ip_'.$product->gtin;
                $p->vendor = isset($p->vendor) ? $p->vendor : 0;
                $p->save();
                // Cập nhật categories
                $cate = array_keys(ProductCategory::
                        where('product_id', $product->id)
                        ->get()
                        ->keyBy('category_id')
                        ->ToArray());
                if($cate) {
                    $categoriesProduct = CategoryProduct::
                    where('product_id', $product->id)
                    ->delete();
                    foreach ($cate as $k) {
                        $pCatygories = new CategoryProduct();
                        $pCatygories->category_id = $k;
                        $pCatygories->product_id =$product->id;
                        $pCatygories->save();
                    }
                }
                // Cộng tiền cho Cộng tác viên
                $collaborators = \App\Models\Enterprise\Collaborator::findOrFail($product->user_id);
                if(!$collaborators) {
                    throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Không tồn tại Công tác viên');
                }
                $collaborators->balance +=  $collaborators->balance + $product->price;
                $collaborators->save();
                unset($collaborators);
            }
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        DB::commit(); 
        return redirect()->back()
            ->with('success', 'Đã cập nhật thành công');
    }
    
    public function cancel(Request $request, $id)
    {   
        $product = ProductDone::findOrFail($id);
        $product->status = ProductDone::STATUS_DISAPPROVED;
        $product->note = $request->input('note');
        $product->update($request->all());
        return redirect()->route('Staff::operation@asy')
            ->with('success', 'Hủy sản phẩm thành công');
    }
    
    public function cancels (Request $request)
    {
        if($request->method() != "POST" || empty($request->input('ids'))) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Error Input Params.');
        }
        $ids = explode(',', $request->input('ids'));
        foreach ( $ids as $i) {
            $product = ProductDone::findOrFail($i);
            $product->status = ProductDone::STATUS_DISAPPROVED;
            $product->note = $request->input('note');
            $product->update($request->all());
        }
        return redirect()->route('Staff::operation@asy')
            ->with('success', 'Hủy nhiều sản phẩm thành công');
    }
}
