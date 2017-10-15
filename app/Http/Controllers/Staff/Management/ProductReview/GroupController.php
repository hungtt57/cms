<?php

namespace App\Http\Controllers\Staff\Management\ProductReview;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\ProductReview\Group;
use App\Models\Enterprise\ProductCategory;
use App\Models\Social\MGroup;
use App\Models\Social\Category;
use App\Models\Social\Product as SocialProduct;
use App\Models\Social\Vendor as SocialVendor;
use App\Models\Social\ProductAttr;
use GuzzleHttp\Exception\RequestException;
use Auth;
use App\Jobs\ProductReview\ImportGroupJob;

class GroupController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-review-group')) {
            abort(403);
        }
        $groups = Group::orderBy('created_at', 'desc')->get();

        return view('staff.management.product_review.group.index', compact('groups'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-review-group')) {
            abort(403);
        }
        $groups = MGroup::select(['id', 'name', 'icon'])->get();

        return view('staff.management.product_review.group.add', compact('groups'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-review-group')) {
            abort(403);
        }
        $this->dispatch(new ImportGroupJob($request->input('groups'), (int) $request->input('max_review')));

        return redirect()->route('Staff::Management::productReview@group@index')
            ->with('success', 'Đã thêm nhóm');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-group')) {
            abort(403);
        }
        $group = Group::findOrFail($id);

        return view('staff.management.product_review.group.edit', compact('group'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-group')) {
            abort(403);
        }
        $group = Group::findOrFail($id);
        $group->update($request->all());

        return redirect()->route('Staff::Management::productReview@group@edit', $group->id)
            ->with('success', 'Đã cập nhật thông tin nhóm');
    }

    public function delete($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('delete-review-group')) {
            abort(403);
        }
        $group = Group::findOrFail($id);
        $group->members()->detach();
        $group->delete();

        return redirect()->route('Staff::Management::productReview@group@index')
            ->with('success', 'Đã xoá nhóm');
    }

    public function batchUpdate(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-review-group')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));

        Group::whereIn('id', $ids)->update($request->only(['max_review']));

        return redirect()->back()
            ->with('success', 'Đã cập nhật thông tin nhóm');
    }

    public function batchDelete(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('delete-review-group')) {
            abort(403);
        }
        $ids = explode(',', $request->input('ids'));

        Group::destroy($ids);

        return redirect()->back()
            ->with('success', 'Đã xoá nhóm');
    }
}
