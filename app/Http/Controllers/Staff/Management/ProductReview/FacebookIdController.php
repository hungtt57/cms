<?php

namespace App\Http\Controllers\Staff\Management\ProductReview;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\ProductReview\FacebookId;
use App\Models\Enterprise\ProductReview\Group;
use App\Models\Enterprise\ProductCategory;
use App\Models\Social\MGroup;
use App\Models\Social\Category;
use App\Models\Social\Product as SocialProduct;
use App\Models\Social\Vendor as SocialVendor;
use App\Models\Social\ProductAttr;
use GuzzleHttp\Exception\RequestException;
use Auth;
use App\Jobs\ProductReview\ImportFacebookIdJob;

class FacebookIdController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-review-facebook')) {
            abort(403);
        }

        $facebookIds = FacebookId::orderBy('created_at', 'desc')->paginate(50);

        return view('staff.management.product_review.facebook_id.index', compact('facebookIds'));
    }

    protected function r($data, $parent = 0, $level = 0) {
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
        if (auth()->guard('staff')->user()->cannot('add-review-facebook')) {
            abort(403);
        }
        $groups = Group::all();

        return view('staff.management.product_review.facebook_id.add', compact('groups'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-review-facebook')) {
            abort(403);
        }
        $ids = preg_split("/\\r\\n|\\r|\\n/", $request->input('ids'));
        $this->dispatch(new ImportFacebookIdJob($ids));

        return redirect()->route('Staff::Management::productReview@facebookId@index')
            ->with('success', 'Đã thêm Facebook ID');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-facebook')) {
            abort(403);
        }
        $product = Product::findOrFail($id);
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories, 12);
        $selectedCategories = ProductCategory::where('product_id', $product->id)->get()->lists('category_id')->toArray();
        $attributes = ProductAttr::all();
        $gln = GLN::where('status', GLN::STATUS_APPROVED)->get();

        return view('staff.management.product.form', compact('product', 'categories', 'selectedCategories', 'attributes', 'gln'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-facebook')) {
            abort(403);
        }
        $product = Product::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'image' => 'image',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('image')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['image'] = $res->prefix;
        }

        $product->update($data);
        $product->gln()->associate($request->input('gln_id'));
        ProductCategory::where(['product_id' => $product->id])->delete();

        foreach ($request->input('categories') as $cat) {
            ProductCategory::create(['product_id' => $product->id, 'category_id' => $cat]);
        }

        $product->save();

        return redirect()->route('Staff::Management::product@edit', $product->id)
            ->with('success', 'Đã cập nhật thông tin sản phẩm Sản phẩm');
    }

    public function delete($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('delete-review-facebook')) {
            abort(403);
        }
        $facebookId = FacebookId::findOrFail($id);
        $facebookId->groups()->detach();
        $facebookId->delete();

        return redirect()->route('Staff::Management::productReview@facebookId@index')
            ->with('success', 'Đã xoá facebookId');
    }

    public function batchDelete(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('delete-review-facebook')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));

        FacebookId::destroy($ids);

        return redirect()->back()
            ->with('success', 'Đã xoá Facebook Id');
    }
}
