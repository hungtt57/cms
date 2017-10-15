<?php

namespace App\Http\Controllers\Ajax\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests\Staff\Management\Business\StoreBusinessRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Models\Social\Product;

use App\Models\Enterprise\GLN;
use App\Models\Enterprise\MICheckReport;


use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use Mail;
use App\Remote\Remote;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\Product2Transformer;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;
use Illuminate\Pagination\Paginator;
use Symfony\Component\HttpFoundation\StreamedResponse;



use App\Models\Icheck\Product\Product;
use App\Models\Icheck\Product\Country;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\AttrDynamic;
use App\Models\Icheck\Product\AttrValue;
use App\Models\Icheck\Product\Category;
use Response;
class Product2Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('export')) {
            set_time_limit(0);
        }

        DB::connection('mongodb')->enableQueryLog();
        $products = Product::with(['reports', 'vendor2', 'categories']);

        if(!$request->has('sort_by')){
            $products= $products->orderBy('updatedAt','desc');
        }
        if ($request->input('q')) {
            $products = $products->where('product_name', 'like', '%' . $request->input('q') . '%');
        }

        if ($request->input('gtin')) {
            $products = $products->where('gtin_code', 'like', '%' . $request->input('gtin') . '%');
        }

        if ($request->input('gln')) {
            $vendor_id = Vendor::where('gln_code',$request->input('gln'))->pluck('id');
            $products = $products->whereIn('vendor_id',$vendor_id);

        }
        if($request->input('verify_owner')){
             $products = $products->where('verify_owner',Product::BUSINESS_VERIFY_OWNER);
        }
        if($request->input('map')){
            $map = $request->input('map');
            if($map == 1){
                $products = $products->where('mapped',1);
            }
            if($map == 2){
                $products = $products->where('mapped',0);
            }
        }
//        $products = $products->where('features','!=','');
        if ($request->has('category')) {
            $category = $request->input('category');

            if ($category == 'none') {
                $products = $products->has('categories', '<=', 0);
            } elseif ($category) {
                $products = $products->whereHas('categories', function ($query) use ($category) {
                    $query->where('category_id', $category);
                });
            }
        }

        if ($request->has('image')) {
            $image = $request->input('image');

            if ($image == 1) {

                $products = $products->where('image_default','!=','');

            } elseif ($image == 2) {
                $products = $products->where(function ($query) {
                    $query->whereNull('image_default')
                        ->orWhere('image_default','');
                });
            }

        }

        if ($request->has('vendor')) {
            $vendor = $request->input('vendor');

            if ($vendor == 1) {
                $products = $products->whereNotNull('vendor_id');
            } elseif ($vendor == 2) {
                $products = $products->where(function ($query) {
                    $query->whereNull('vendor_id')
                        ->orWhere('vendor_id', '');
                });
            }
        }

        if ($request->has('price')) {
            $price = trim($request->input('price'));

            if (substr($price, 0, 1) != '='
                and substr($price, 0, 1) != '<'
                and substr($price, 0, 1) != '>'
                and substr($price, 0, 2) != '<>'
                and substr($price, 0, 2) != '>='
                and substr($price, 0, 2) != '<='
            ) {
                $price = (int) trim($price);
                $op = '=';
            } elseif (substr($price, 0, 1) != '='
                or substr($price, 0, 1) != '<'
                or substr($price, 0, 1) != '>'
            ) {
                $op = substr($price, 0, 1);
                $price = (int) trim(substr($price, 1));
            } else {
                $op = substr($price, 0, 2);
                $price = (int) trim(substr($price, 2));
            }

            $products = $products->whereRaw('`price_default` ' . $op . ' ' . $price);
        }

        // if ($request->has('status') and $request->input('status') !== '') {
        //     $products = $products->where('status', $request->input('status'));
        // }

        $sortBy2Field = [
            'name' => 'product_name',
            'created_at' => 'createdAt',
            'price' => 'price_default',
        ];
        $mongodbSort = [
            'scan_count',
            'view_count',
            'like_count',
            'vote_count',
            'comment_count',
        ];

        if ($request->has('sort_by') and in_array($request->input('sort_by'), array_keys($sortBy2Field))) {
            $products = $products->orderBy($sortBy2Field[$request->input('sort_by')], $request->input('order', 'asc'));
        }

        $fractal = new Manager();

        if ($request->has('sort_by') and in_array($request->input('sort_by'), $mongodbSort)) {
            if ($request->has('export')) {

                $products =$products->orderBy($request->input('sort_by'), $request->input('order', 'asc'));
                $response = new StreamedResponse(function () use ($products) {
                    // Open output stream
                    $handle = fopen('php://output', 'w');

                    // Add CSV headers
                    fputcsv($handle, [
                        'GTIN',
                        'Name',
                        'Price',
                        'GLN',
                        'ScanCount',
                        'ViewCount',
                        'LikeCount',
                        'VoteCount',
                        'CommentCount',
                    ]);

                    $products->chunk(250, function ($products) use ($handle) {

                        foreach ($products as $product) {
                            // Add a new row with data
                            fputcsv($handle, [
                                $product->gtin_code,
                                $product->product_name,
                                $product->price_default,
                                @$product->vendor->gln_code,
                                @$product->scan_count,
                                @$product->view_count,
                                @$product->like_count,
                                @$product->vote_count,
                                @$product->vote_good_count + $product->vote_normal_count + $product->vote_bad_count,
                                @$product->comment_count,
                            ]);
                        }
                    });

                    // Close the output stream
                    fclose($handle);
                }, 200, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="export.csv"',
                ]);

                return $response;
            } else {

                $productCount = clone $products;
                $productCount = $productCount->count();

                $products =$products->orderBy($request->input('sort_by'), $request->input('order', 'asc'));
                $products = $products->take((int) $request->input('per_page', 10))->get();
                $products = new LengthAwarePaginator($products, $productCount, (int) $request->input('per_page', 10));

            }
        } elseif ($request->input('sort_by') == 'report_count') {
            if ($request->has('export')) {
                //$order = $request->input('order') == 'desc' ? -1 : 1;
                $count = MICheckReport::raw(function ($collection) use ($request) {
                    $order = $request->input('order') == 'desc' ? -1 : 1;

                    return $collection->aggregate([
                        [
                            '$group' => [
                                "_id" => '$target',
                                "count" => [
                                    '$sum' => 1,
                                ],
                            ],
                        ],
                        [
                            '$sort' => [
                                'count' => $order,
                            ],
                        ],
                    ]);
                });

                $response = new StreamedResponse(function () use ($count, $products) {
                    // Open output stream
                    $handle = fopen('php://output', 'w');

                    // Add CSV headers
                    fputcsv($handle, [
                        'GTIN',
                        'Name',
                        'Price',
                        'GLN',
                        'ScanCount',
                        'ViewCount',
                        'LikeCount',
                        'VoteCount',
                        'CommentCount',
                    ]);

                    $products = $products->whereIn('gtin_code', $count->lists('_id')->toArray());

                    foreach ($count->lists('_id')->toArray() as $gtin) {
                        $products = $products->orderByRaw('`gtin_code` = \'' . $gtin . '\' desc');
                    }

                    $products->chunk(500, function ($products) use ($handle) {
                        foreach ($products as $product) {
                            // Add a new row with data
                            fputcsv($handle, [
                                $product->gtin_code,
                                $product->product_name,
                                $product->price_default,
                                @$product->vendor->gln_code,
                                @$product->scan_count,
                                @$product->view_count,
                                @$product->like_count,
                                @$product->vote_count,
                                @$product->vote_good_count + $product->vote_normal_count + $product->vote_bad_count,
                                @$product->comment_count,
                            ]);
                        }
                    });

                    // Close the output stream
                    fclose($handle);
                }, 200, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="export.csv"',
                ]);

                return $response;
            }
            else {
                //$order = $request->input('order') == 'desc' ? -1 : 1;
                $count = MICheckReport::raw(function ($collection) use ($request) {
                    $page = Paginator::resolveCurrentPage();
                    $parPage = $request->input('per_page', 10);
                    $order = $request->input('order') == 'desc' ? -1 : 1;

                    return $collection->aggregate([
                        [
                            '$group' => [
                                "_id" => '$target',
                                "count" => [
                                    '$sum' => 1,
                                ],
                            ],
                        ],
                        [
                            '$sort' => [
                                'count' => $order,
                            ],
                        ],
                        // [
                        //     '$match' => [
                        //         'status' => ['$eq' => 0],
                        //     ],
                        // ],
                        [
                            '$limit' => ($page - 1) * $parPage + $parPage,
                        ],
                        [
                            '$skip' => ($page - 1) * $parPage,
                        ],
                    ]);
                });
                // MICheckReport::raw()->aggregate([
                //         [
                //             '$group' => [
                //                 "_id" => '$target',
                //                 "count" => [
                //                     '$sum' => 1,
                //                 ],
                //             ],
                //         ],
                //         [
                //             '$sort' => [
                //                 'count' => $order,
                //             ],
                //         ],
                //     ])->get();

                $productCount = clone $products;
                $productCount = $productCount->count();

                foreach ($count->lists('_id')->toArray() as $gtin) {
                    $products = $products->orderByRaw('`gtin_code` = \'' . $gtin . '\' desc');
                }
                $products = $products->take((int) $request->input('per_page', 10))->get();
                $products = new LengthAwarePaginator($products, $productCount, (int) $request->input('per_page', 10));
            }
        } else {
            if ($request->has('export')) {

                $response = new StreamedResponse(function () use ($products) {
                    // Open output stream
                    $handle = fopen('php://output', 'w');

                    // Add CSV headers
                    fputcsv($handle, [
                        'GTIN',
                        'Name',
                        'Price',
                        'GLN',
                        'ScanCount',
                        'ViewCount',
                        'LikeCount',
                        'VoteCount',
                        'CommentCount',
                    ]);

                    $products->chunk(500, function ($products) use ($handle) {
                        foreach ($products as $product) {
                            // Add a new row with data
                            fputcsv($handle, [
                                $product->gtin_code,
                                $product->product_name,
                                $product->price_default,
                                @$product->vendor->gln_code,
                                @$product->scan_count,
                                @$product->view_count,
                                @$product->like_count,
                                @$product->vote_good_count + $product->vote_normal_count + $product->vote_bad_count,
                                @$product->comment_count,
                            ]);
                        }
                    });

                    // Close the output stream
                    fclose($handle);
                }, 200, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="export.csv"',
                ]);

                return $response;
            }

            $products = $products->paginate((int) $request->input('per_page', 20));
        }
        $resource = new Collection($products->getCollection(), new Product2Transformer($this->getCategoriesNotSub()));
        $products->appends($request->all());
        $resource->setPaginator(new IlluminatePaginatorAdapter($products));

//        $products = $fractal->createData($resource)->toJson();

        return $fractal->createData($resource)->toArray();
    }


    public function getAttributesByProduct(Request $request){
        $selected = $request->input('selected');
        $id = $request->input('id');
        $product = Product::find($id);
        $attr_value = AttrValue::where('product_id',$product->id)->get();
        $result = [];
        $att_array = [];
        if(empty($attr_value)){
            return json_encode($result);
        }
        $categories = Category::whereIn('id',$selected)->get();
        if($categories){
            foreach ($categories as $category){
                $attributes = $category->attributes;
                if($attributes){
                    $attributes = explode(',', $attributes);
                    foreach ($attributes as $at) {
                        if(isset($att_array[$at])){
                            $att_array[$at] = intval($att_array[$at]) + 1;
                        }else{
                            $att_array[$at] = 1;
                        }
                    }
                }

            }
        }

        if($attr_value){
            foreach ($attr_value as $key => $value) {
                $property = AttrDynamic::find($value->attribute_id);
                if($property){
                    $properties = explode(',',$value->content);
                    $count = 1;
                    if(isset($attr_array[$property->id])){
                        $count = $attr_array[$property->id];
                    }
                    $result[$property->id] = $this->templateEdit($property,$count,$properties);
                }
            }
        }
        return json_encode($result);
    }
    public function templateEdit($attr,$count = 1,$properties)
    {
        $string = null;
        if($attr->enum){
            if(trim($attr->type) == 'single'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    if(isset($properties)){
                        if(in_array($v,$properties)){
                            $s .=   '<option value="'.$v.'" selected>'.$v.'</option>';
                        }
                    }else{
                        $s .=   '<option value="'.$v.'">'.$v.'</option>';
                    }

                }
                $string = '<div class="row" data-count="'.$count.'" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select  name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
            if(trim($attr->type) == 'multiple'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    if(isset($properties)){
                        if(in_array($v,$properties)){
                            $s .=   '<option value="'.$v.'" selected>'.$v.'</option>';
                        }
                    }else{
                        $s .=   '<option value="'.$v.'">'.$v.'</option>';
                    }
                }
                $string = '<div class="row" data-count="'.$count.'" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr"  multiple="multiple">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        }else{
            $t = '';

            if(isset($properties)){
                $properties = implode(',',$properties);
                $t = 'value="'.$properties.'"';
            }
            $string = '<div class="row" data-count="'.$count.'" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <input maxlength="25" name="properties['.$attr->id.'][]" '.$t.' type="text" class="form-control border-primary">
                        
                                                  
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }
    public function getAttributesByCategory(Request $request){
        $category_id = $request->input('id');
        $category = Category::find($category_id);
        $result = [];
        if ($category) {
            $attributes = $category->attributes;
            if ($attributes) {
                $attributes = explode(',', $attributes);
                $attrs = AttrDynamic::whereIn('id', $attributes)->get();
                foreach ($attrs as $attr) {
                    $result[$attr->id] = $this->template($attr);
                }
            }
        }
        return json_encode($result);
    }
    public function template($attr)
    {
        $string = null;
        if($attr->enum){
            if(trim($attr->type) == 'single'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    $s .=   '<option value="'.$v.'">'.$v.'</option>';
                }
                $string = '<div class="row" data-count="1" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select  name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
            if(trim($attr->type) == 'multiple'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    $s .=   '<option value="'.$v.'">'.$v.'</option>';
                }
                $string = '<div class="row" data-count="1" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select name="properties['.$attr->id.'][]" class="select-border-color border-warning js-attr"  multiple="multiple">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        }else{
            $string = '<div class="row" data-count="1" id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <input  maxlength="25" name="properties['.$attr->id.'][]" type="text" class="form-control border-primary">
                        
                                                  
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }
    public static function getCategoriesNotSub(){
        $categories = Category::has('child','<=',0)->get();
        return $categories;
    }

    public function updateAttrInline(Request $request){
        $product_id = $request->input('product_id');
        $attr_id  = $request->input('attr_id');
        $value = $request->input('value');
        if($value){
            if(is_array($value)){
                $value = implode(',',$value);
            }

            $attr_value = [
                'product_id' =>$product_id,
                'attribute_id' => $attr_id,
            ];
            if($value){
                $product = Product::find($product_id);
                $a = AttrValue::firstOrCreate($attr_value);

                $a->content = $value;
                $a->save();
                $attr_value = AttrValue::where('product_id',$product_id)->get();
                $count = 0;
                $arrValue = [];
                foreach ($attr_value as $value){
                    if($count < 10){
                        if($value->content){
                            $arrValue[] = $value->content;
                        }
                        $count++;
                    }
                }
                if($arrValue){
                    $arrValue = implode(',',$arrValue);
                    $product->features = $arrValue;
                    $product->save();
                }
                return Response::json(['features'=> $product->features], 200);
            }
        }else{
            AttrValue::where('product_id',$product_id)->where('attribute_id',$attr_id)->delete();
            $attr_value = AttrValue::where('product_id',$product_id)->get();
            $product = Product::find($product_id);
            $count = 0;
            $arrValue = [];
            foreach ($attr_value as $value){
                if($count < 10){
                    if($value->content){
                        $arrValue[] = $value->content;
                    }
                    $count++;
                }
            }

            if($arrValue){
                $arrValue = implode(',',$arrValue);
                $product->features = $arrValue;
                $product->save();
            }else{

                $product->features = '';
                $product->save();
            }

            return Response::json(['features'=> $product->features,'delete' => true], 200);
        }
    }
    public function addAttrInline(Request $request){
        $category_id = $request->input('id');
        $product_id =  $request->input('product_id');
        $category = Category::find($category_id);
        $result = [];
        if ($category) {
            $attributes = $category->attributes;
            if ($attributes) {
                $attributes = explode(',', $attributes);
                $attrs = AttrDynamic::whereIn('id', $attributes)->get();
                foreach ($attrs as $attr) {
                    $result[$attr->id] = $this->templateInline($attr,$product_id);
                }
            }
        }
        return json_encode($result);
    }
    public function templateInline($attr,$product_id)
    {
        $string = null;
        if($attr->enum){
            if(trim($attr->type) == 'single'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    $s .=   '<option value="'.$v.'">'.$v.'</option>';
                }
                $string = '<div class="row"  id="'.$product_id.$attr->id.'" data-count="1" data-id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select class="select-border-color border-warning js-attr  properties-product">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
            if(trim($attr->type) == 'multiple'){
                $value = $attr->enum;
                $value = explode(',',$value);
                $s = '';
                foreach ($value as $v){
                    $s .=   '<option value="'.$v.'">'.$v.'</option>';
                }
                $string = '<div class="row"  id="'.$product_id.$attr->id.'" data-count="1" data-id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <select  class="select-border-color border-warning js-attr  properties-product"  multiple="multiple">
                                                   '.$s.'
                                                    </select>
                                                </div>
                                            </div>
                       ';
            }
        }else{
            $string = '<div class="row"  id="'.$product_id.$attr->id.'" data-count="1" data-id="'.$attr->id.'"><div class="col-md-6"><label for="country" class="control-label text-semibold">'.$attr->title.'</label></div><div class="col-md-6">
                        <input  maxlength="25" type="text" class="form-control properties-product">
                        
                                                  
                                                </div>
                                            </div>
                       ';
        }

        return $string;
    }
    public function getAttrIdCategory(Request $request){
        $categoryId = $request->input('category_id');
        $attributes ='';
        if($categoryId){
            $category = Category::find($categoryId);
            if($category){
                $attributes = $category->attributes;
            }
        }
        return $attributes;

    }
}
