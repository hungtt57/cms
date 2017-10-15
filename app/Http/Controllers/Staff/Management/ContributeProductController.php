<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Remote\Remote;
use App\Models\Collaborator\ContributeProduct as Product;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use App\Jobs\ApproveContributeProductJob;
use App\Jobs\ImportContributeProductJob;
use App\Models\Enterprise\Collaborator;


use App\Models\Icheck\Product\Product as SocialProduct;
use App\Models\Icheck\Product\Vendor as SocialVendor;

use App\Models\Enterprise\CollaboratorGroup;
class ContributeProductController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-contribute-product')) {
            abort(403);
        }
        $selectedCondition = null;
        $contributors = Collaborator::all();

        $products = Product::with('contributor')
            ->orderBy('updatedAt');

        if ($createdAtFrom = $request->input('created_at_from')) {
            $products = $products->where('contributedAt', '>=', Carbon::createFromFormat('Y-m-d', $createdAtFrom)->startOfDay());
        }

        if ($createdAtTo = $request->input('created_at_to')) {
            $products = $products->where('contributedAt', '<=', Carbon::createFromFormat('Y-m-d', $createdAtTo)->endOfDay());
        }

        if ($gtin = $request->input('gtin')) {
            $products = $products->where('gtin','like', '%'.$gtin.'%');
        }

        if ($group = $request->input('group')) {
            $products = $products->where('group', $group);
        }

        if ($contributor = $request->input('contributor')) {
            $products = $products->where('contributorId', intval($contributor));
        }
        $conditions = [
            'name' => 'Có tên',
            'images' => 'Có ảnh',
            'categories' => 'Có danh mục',
            'properties' => 'Có thuộc tính'
        ];
        if($cs = $request->input('condition')){

            $selectedCondition = $cs;
            if(in_array('name',$cs)){
                $products = $products->where('name','!=', null);
            }else{
                $products = $products->where('name', null);
            }
            if(in_array('images',$cs)){
                $products = $products->where('images','!=', []);
            }else{
                $products = $products->where('images', []);
            }
            if(in_array('categories',$cs)){
                $products = $products->where('categories','!=', []);
            }else{
                $products = $products->where('categories', []);
            }
            if(in_array('properties',$cs)){

                $products = $products->where('properties','!=', []);
            }else{
                $products = $products->where('properties', []);
            }
        }
        $count0 = clone $products;
        $count0 = $count0->where('status', Product::STATUS_PENDING_APPROVAL)->count();

        $count1 = clone $products;
        $count1 = $count1->where('status', Product::STATUS_DISAPPROVED)->count();

        $count2 = clone $products;
        $count2 = $count2->where('status', Product::STATUS_APPROVED)->count();

        $count3 = clone $products;
        $count3 = $count3->where('status', Product::STATUS_IN_PROGRESS)->count();

        $count4 = clone $products;
        $count4 = $count4->where('status', Product::STATUS_ERROR)->count();

        $count5 = clone $products;
        $count5 = $count5->where('status', Product::STATUS_AVAILABLE_CONTRIBUTE)->count();

        if ($request->has('status') and ($status = $request->input('status')) !== '') {

//            $duplicates = $products->raw(function($collection) use ($status) {
//                return $collection->aggregate(
//                    [
//                        [
//                            '$match' => [
//                                'status' => [
//                                    '$gte' => intval($status)
//                                ]
//                            ]
//                        ]
//                    ],
//                    [
//                        'allowDiskUse' => true,
//                    ]
//                );
//            });
//            dd($duplicates);
            $products = $products
                ->where('status', intval($status))->paginate(intval($request->input('lm', '100')));

        } else {
            $products = $products
                ->whereIn('status', [Product::STATUS_PENDING_APPROVAL, Product::STATUS_ERROR, Product::STATUS_IN_PROGRESS, Product::STATUS_AVAILABLE_CONTRIBUTE])->paginate(100);
        }
        $groups = CollaboratorGroup::all();

        return view('staff.management.contribute_product.index', compact('conditions','selectedCondition','groups','products', 'count0', 'count1', 'count2', 'count3', 'count4', 'count5', 'contributors'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-review-product')) {
            abort(403);
        }
        $types = [
            1 => 'Không có tên',
            2 => 'LOG SCAN NOT FOUND',
            3 => 'Có tên,không có ảnh,không danh mục',
            4 => 'Có tên,có ảnh,không có danh mục',
            5 => 'Có tên,không ảnh,có danh mục',
            6 => 'Nhập danh sách mã',
            7 => 'Mã kém chất lượng'
        ];
        $groups = CollaboratorGroup::all();
        return view('staff.management.contribute_product.add',compact('types','groups'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-contribute-product')) {
            abort(403);
        }
//        1 => 'Không có tên',
//            2 => 'LOG SCAN NOT FOUND',
//            3 => 'Có tên,không có ảnh,không danh mục',
//            4 => 'Có tên,có ảnh,không có danh mục',
//            5 => 'Có tên,không ảnh,có danh mục',
//            6 => 'Nhập danh sách mã',
//            7 => 'Mã kém chất lượng'

        $this->validate($request, [
            'type' => 'required',
        ]);
        $gtin = '';
        $type = $request->input('type');
        if($type != 6 ) {
            if(!$request->input('quantity')){
                return redirect()->back()->with('error','Vui lòng nhập số lượng');
            }
        }
        $quantity = $request->input('quantity');
        if($type == 6){
            if(!$request->input('gtin') && !$request->input('gln')){
                return redirect()->back()->with('error','Vui lòng nhập danh sách mã gtin hoặc gln');
            }
        }
        $data = $request->all();
        $this->dispatch(new ImportContributeProductJob($data,auth('staff')->user()->email));

        return redirect()->route('Staff::Management::contributeProduct@index')
            ->with('success', 'Đã thêm sản phẩm');
    }

    public function approve($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $review = Product::findOrFail($id);
        $amount = 500;

        if ($request->input('amount')) {
            $amount = (int) $request->input('amount');
        }

        if ($review->amount) {
            $amount = $review->amount;
        }

        $this->dispatch(new ApproveContributeProductJob($review, $request->input('note'), $amount, auth()->guard('staff')->user()->id,auth('staff')->user()->email));

        return redirect()->back()
            ->with('success', 'Bài đánh giá đã được thêm vào queue');
    }

    public function disapprove($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $review = Product::findOrFail($id);
        $review->update([
            'note' => $request->input('note'),
            'status' => Product::STATUS_DISAPPROVED,
        ]);

        return redirect()->back()
            ->with('success', 'Bài đánh giá đã bị huỷ');
    }

    public function batchApprove(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));
        $reviews = Product::whereIn('_id', $ids)->get();
        $amount = 500;

        if ($request->input('amount')) {
            $amount = (int) $request->input('amount');
        }

        foreach ($reviews as $review) {
            if ($review->amount) {
                $amount2 = $review->amount;
            } else {
                $amount2 = $amount;
            }

            $this->dispatch(new ApproveContributeProductJob($review, $request->input('note'), $amount2, auth()->guard('staff')->user()->id,auth('staff')->user()->email));
        }

        return redirect()->back()
            ->with('success', 'Bài đánh giá đã được thêm vào queue');
    }

    public function batchDisapprove(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));
        $reviews = Product::whereIn('_id', $ids)->get();

        foreach ($reviews as $review) {
            $review->update([
                'note' => $request->input('note'),
                'status' => Product::STATUS_DISAPPROVED,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Bài đánh giá đã bị huỷ');
    }

    public function delete($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $review = Product::findOrFail($id);
        $review->delete();

        return redirect()->back()
            ->with('success', 'Deleted');
    }

    public function batchDelete(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));
        $reviews = Product::whereIn('_id', $ids)->delete();

        return redirect()->back()
            ->with('success', 'Deleted');
    }

    public function changeGroup(Request $request){

        $group = $request->input('group');
        $ids = $request->input('ids');
        $ids = explode(',',$ids);
        Product::whereIn('_id',$ids)->where('status',Product::STATUS_AVAILABLE_CONTRIBUTE)->where('contributorId',null)->update(['group' => $group]);
        return redirect()->back()
            ->with('success', 'Đã chuyển group thành công');
    }

    public function addInlineGln($id,Request $request){
        $product = Product::where('_id',$id)->first();
        $gln = $request->input('gln_code');
        $product->gln_code = $gln;
        $product->save();
       return 'oke';
    }

}
