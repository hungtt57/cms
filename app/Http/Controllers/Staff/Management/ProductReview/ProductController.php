<?php

namespace App\Http\Controllers\Staff\Management\ProductReview;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\ProductReview\Product;
use App\Models\Enterprise\ProductCategory;
use App\Models\Social\Category;
use App\Models\Social\Product as SocialProduct;
use App\Models\Social\Vendor as SocialVendor;
use App\Models\Social\ProductAttr;
use GuzzleHttp\Exception\RequestException;
use Auth;
use App\Jobs\ProductReview\ImportProductJob;

class ProductController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-review-product')) {
            abort(403);
        }
        $products = Product::orderBy('created_at', 'desc')->paginate(20);

        return view('staff.management.product_review.product.index', compact('products'));
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
        if (auth()->guard('staff')->user()->cannot('add-review-product')) {
            abort(403);
        }
        return view('staff.management.product_review.product.add');
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-review-product')) {
            abort(403);
        }
        if ($request->has('gln')) {
            $gln = preg_split("/\\r\\n|\\r|\\n/", $request->input('gln'));

            $vendorIds = SocialVendor::select(['id'])->whereIn('gln_code', $gln)->get()->lists('id')->toArray();
            $gtin = SocialProduct::select(['gtin_code'])->whereIn('vendor', $vendorIds)->get()->lists('gtin_code')->toArray();
        } else {
            $gtin = preg_split("/\\r\\n|\\r|\\n/", $request->input('gtin'));
            $gtin = SocialProduct::select(['gtin_code'])->whereIn('gtin_code', $gtin)->get()->lists('gtin_code')->toArray();
        }

        $this->dispatch(new ImportProductJob($gtin, (int) $request->input('max_review')));

        return redirect()->route('Staff::Management::productReview@product@index')
            ->with('success', 'Đã thêm sản phẩm');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-product')) {
            abort(403);
        }
        $product = Product::findOrFail($id);

        return view('staff.management.product_review.product.edit', compact('product'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-product')) {
            abort(403);
        }
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('Staff::Management::productReview@product@edit', $product->id)
            ->with('success', 'Đã cập nhật thông tin sản phẩm');
    }

    public function delete($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('delete-review-product')) {
            abort(403);
        }
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('Staff::Management::productReview@product@index')
            ->with('success', 'Đã xoá sản phẩm');
    }

    public function batchUpdate(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-product')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));

        Product::whereIn('id', $ids)->update($request->only(['max_review']));

        return redirect()->back()
            ->with('success', 'Đã cập nhật thông tin sản phẩm');
    }

    public function batchDelete(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('delete-review-product')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));

        Product::destroy($ids);

        return redirect()->back()
            ->with('success', 'Đã xoá Sản phẩm');
    }
}
