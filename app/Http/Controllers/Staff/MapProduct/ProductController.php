<?php

namespace App\Http\Controllers\Staff\MapProduct;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use App\Models\Icheck\Product\Product;
use App\Models\Craw\Product as ProductCraw;
use App\Jobs\MapProduct;
use App\Models\Icheck\Product\Vendor;
use Illuminate\Support\Facades\Cache;
use Response;

class ProductController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('DOMAIN_API'),
            'auth' => [env('USER_API'), env('PASS_API')],
            'timeout' => 3.0,
        ]);
    }

    public function index(Request $request)
    {
        $conditions = [
            'name' => 'Có tên',
            'images' => 'Có ảnh',
        ];
        $products = Product::select('*')->where('verify_owner', '!=', Product::BUSINESS_VERIFY_OWNER);
        if ($request->input('name')) {
            $products = $products->where('product_name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('gtin')) {
            $products = $products->where('gtin_code', 'like', '%' . $request->input('gtin') . '%');
        }
        if ($request->input('gln')) {
            $vendor_id = Vendor::where('gln_code', $request->input('gln'))->pluck('id');
            $products = $products->whereIn('vendor_id', $vendor_id);

        }
        $selectedCondition = null;
        if ($cs = $request->input('condition')) {

            $selectedCondition = $cs;
            if (in_array('name', $cs)) {
                $products = $products->where('product_name', '!=', '');
            } else {
                $products = $products->where('product_name', '');
            }
            if (in_array('images', $cs)) {
                $products = $products->where('image_default', '!=', '');
            } else {
                $products = $products->where('image_default', '');
            }


        }
        $products = $products->where('mapped',0)->paginate(10);
        foreach ($products as $product) {
            $product->map_product = [];
//            if(Cache::has($product->id)){
//                $product->map_product = Cache::get($product->id);
//                continue;
//            }

            try {
                if ($request->input('crawCondition')) {
                    $key = $request->input('crawCondition');
                    if ($key == 1) {
                        $response = $this->client->get('search', [
                            'query' => [
                                'type' => 'sale_web',
                                'barcode' => $product->gtin_code,
                            ]
                        ]);

                        $data = json_decode((string)$response->getBody(), true);
                        if ($data['status'] == 200) {
                            if ($data['data']['items']) {
                                $items = $data['data']['items'];
                                $product->map_product = $items;

                            }
                        }

                    }
                    if ($key == 2) {
                        if($product->product_name){
                            $response = $this->client->get('search', [
                                'query' => [
                                    'type' => 'sale_web',
                                    'query' => $product->product_name
                                ]
                            ]);
                            $data = json_decode((string)$response->getBody(), true);
                            if ($data['status'] == 200) {
                                if ($data['data']['items']) {
                                    $items = $data['data']['items'];
                                    $product->map_product = $items;
                                }

                            }
                        }

                    }
                } else {
                    $response = $this->client->get('search', [
                        'query' => [
                            'type' => 'sale_web',
                            'barcode' => $product->gtin_code,
                        ]
                    ]);

                    $data = json_decode((string)$response->getBody(), true);
                    if ($data['status'] == 200) {
                        if ($data['data']['items']) {
                            $items = $data['data']['items'];
                            $product->map_product = $items;
                        } else {
                            if ($product->product_name) {
                                $response = $this->client->get('search', [
                                    'query' => [
                                        'type' => 'sale_web',
                                        'query' => $product->product_name
                                    ]
                                ]);
                                $data = json_decode((string)$response->getBody(), true);
                                if ($data['status'] == 200) {
                                    if ($data['data']['items']) {
                                        $items = $data['data']['items'];
                                        $product->map_product = $items;
                                    }

                                }
                            }

                        }
                    }
                }


                if ($product->map_product) {
                    Cache::put($product->id, $product->map_product, 60);
                }


            } catch (\Exception $exception) {
                continue;
            }
        }

        return view('staff.mapProduct.product.index', compact('products', 'conditions', 'selectedCondition'));
    }

    public
    function mapList(Request $request)
    {
        $this->validate($request, [
            'selected' => 'required'
        ], [
            'selected.required' => 'Vui lòng tích chọn sản phẩm'
        ]);
        $data = [];
        $ids = $request->input('selected');
        foreach ($ids as $id) {
            if (Cache::has($id)) {
                $data[$id] = Cache::get($id);
                Cache::forget($id);
            }
        }
        $mapProductId = $request->input('list_map_product');
        $email = auth()->guard('staff')->user()->email;
        $this->dispatch(new MapProduct($ids, $mapProductId, $data, $email));
        return redirect()->back()->with('success', 'Lên lịch map sản phẩm thành công');
    }

    public
    function inline(Request $request, $id, $productId)
    {

        if (Cache::has($productId)) {
            $productHT = Cache::get($productId);
            $position = 0;
            foreach ($productHT as $key => $p) {
                if ($p['id'] == $id) {
                    $position = $key;
                }
            }


            if ($request->input('name')) {
                $productHT[$position]['text'] = $request->input('name');
            }
            if ($request->input('price')) {
                $productHT[$position]['price'] = $request->input('price');
            }
            if ($request->input('description')) {
                $productHT[$position]['description'] = $request->input('description');
            }
            if ($request->input('images')) {
                $data = $request->input('images');
                if ($data != 'del-all') {
                    $productHT[$position]['images'] = $data;

                } else {
                    unset($productHT[$position]['images']);
                }
            }
            Cache::put($productId, $productHT, 60);
            return 'oke';
        } else {
            return Response::json([], 404);
        }

    }
}
