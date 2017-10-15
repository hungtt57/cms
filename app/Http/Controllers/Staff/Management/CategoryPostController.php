<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Icheck\Social\CategoryPost;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Icheck\Product\AttrDynamic;
use App\Models\Icheck\Product\AttrValue;
use App\Models\Icheck\Social\Category;
use DB;
class CategoryPostController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-category-post')) {
            abort(403);
        }
        $categories = Category::orderBy('updatedAt','desc')->paginate(20);
        return view('staff.management.category_post.index',compact('categories'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-category-post')) {
            abort(403);
        }

        return view('staff.management.category_post.form');

    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-category-post')) {
            abort(403);
        }
        $this->validate($request, [
            'name' =>'required',

        ]);

        $category = Category::create($request->all());

        return redirect()->route('Staff::Management::categoryPost@index')
            ->with('success', 'Đã thêm Category Post');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-category-post')) {
            abort(403);
        }
        $cat = Category::findOrFail($id);
        return view('staff.management.category_post.form',compact('cat'));
    }


    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-category-post')) {
            abort(403);
        }
        $this->validate($request, [
            'name' =>'required',

        ]);
        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('Staff::Management::categoryPost@index',$category->id)
            ->with('success', 'Đã cập nhật thông tin Category');
    }

    public function delete($id){
        if (auth()->guard('staff')->user()->cannot('delete-category-post')) {
            abort(403);
        }
        $category = Category::findOrFail($id);
        CategoryPost::where('category_id',$category->id)->delete();
        $category->delete();

        return redirect()->route('Staff::Management::category@index')->with('success', 'Đã xoá thành công');
    }


}
