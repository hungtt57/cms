<?php
/**
 * Created by PhpStorm.
 * User: Hieu1
 * Date: 6/22/16
 * Time: 08:57
 */

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Models\Social\Category;
use App\Models\Icheck\Product\Category;
use App\Models\Collaborator\ContributeProduct;
use App\Models\Icheck\Product\AttrDynamic;
use App\Models\Icheck\Product\AttrValue;
use DB;
class CategoryController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-category')) {
            abort(403);
        }
        $category = Category::all()->groupBy('parent_id');
        $category = $this->r($category,0);

        return view('staff.management.category.index',compact('category'));
    }


    public static function r($data, $parent = 0, $level = 0) {
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
        if (auth()->guard('staff')->user()->cannot('add-category')) {
            abort(403);
        }
        $category = Category::all()->groupBy('parent_id');
        $category = $this->r($category, 0);
        $attrs = AttrDynamic::all();
        return view('staff.management.category.form', compact('category','attrs'));

    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-category')) {
            abort(403);
        }
        $this->validate($request, [
            'name_category' =>'required',

        ]);

        $category = new Category();
        $category->name = $request->input('name_category');
        $category->parent_id = $request->input('id_parent');
        if($request->input('attrs')){
            $attr = $request->input('attrs');
            $attr = implode(',',$attr);
            $category->attributes = $attr;
        }
        $category->save();

        return redirect()->route('Staff::Management::category@index')
            ->with('success', 'Đã thêm Category');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-category')) {
            abort(403);
        }
        $cat = Category::findOrFail($id);
        $attributes = $cat->attributes;
        $attributes = explode(',',$attributes);

        $category = Category::all();
        $attrs = AttrDynamic::all();
        return view('staff.management.category.form',compact('category','cat','attributes','attrs'));
    }


    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-category')) {
            abort(403);
        }
        $this->validate($request, [
            'name_category' =>'required',

        ]);
        $category = Category::findOrFail($id);
        $category->name = $request->input('name_category');
        if($id != 12){
            $category->parent_id = $request->input('id_parent');
        }

        if($request->input('attrs')){
            $attr = $request->input('attrs');
            $attr = implode(',',$attr);
            $category->attributes = $attr;
        }
        $category->save();

        return redirect()->route('Staff::Management::category@index',$category->id)
            ->with('success', 'Đã cập nhật thông tin Category');
    }

    public function delete($id){
        if (auth()->guard('staff')->user()->cannot('delete-category')) {
            abort(403);
        }
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('Staff::Management::category@index')->with('success', 'Đã xoá thành công');
    }


    public function addAttr(Request $request){

       $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories,0);
        return view('staff.management.category.addAttr',compact('categories'));
    }
    public function listAttr(Request $request){
        if (auth()->guard('staff')->user()->cannot('category-list-category-attr')) {
            abort(403);
        }
        $attrs = AttrDynamic::orderBy('id','desc')->paginate(20);
        return view('staff.management.category.listAttr',compact('attrs'));
    }
    public function addAttrPost(Request $request){

        $this->validate($request, [
            'title' =>'required|unique:icheck_product.attr_dynamic',
            'key' =>'required',

        ]);
        $data = $request->all();
       if(isset($data['enum']) and $data['enum']){
           $data['enum'] = implode(',',$data['enum']);
       }else{
           $data['enum'] = '';
           $data['type'] = '';
       }
       if(isset( $data['type'])){
           $data['type'] = trim($data['type']);
       }

        $attr = AttrDynamic::create($data);
        if(isset($data['categories'])){
            $categories = $data['categories'];
            foreach ($categories as $category){
                $c = Category::find($category);
                $attrs = $c->attributes;
                if($attrs){
                    $attrs = $attrs.','.$attr->id;
                }else{
                    $attrs = $attr->id;
                }
                $c->attributes = $attrs;
                $c->save();
            }
        }
        return redirect()->route('Staff::Management::category@listAttr')->with('success', 'Thêm thuộc tính thành công');
    }
    public function editAttr(Request $request ,$id){
        $categories = Category::all()->groupBy('parent_id');
        $categories = $this->r($categories,0);

        $attr = AttrDynamic::find($id);
        $selected_category = Category::where('attributes',$id)
            ->orWhere('attributes','like',$id.','.'%')
            ->orWhere('attributes','like','%'.','.$id)
            ->orWhere('attributes','like','%'.','.$id.','.'%')
            ->get()->lists('id')->toArray();
        return view('staff.management.category.addAttr',compact('categories','attr','selected_category'));

    }
    public function updateAttr(Request $request,$id){
        $this->validate($request, [
            'title' =>'required',
            'key' =>'required',
        ]);
        $attr = AttrDynamic::find($id);
        $data = $request->all();
        if(isset($data['enum'])){
            $data['enum'] = implode(',',$data['enum']);

        }else{
            $data['enum'] = '';
            $data['type'] = '';
        }
        if(isset($data['type'])){
            $data['type'] = trim($data['type']);
        }else{
            $data['type'] = '';
        }

        $attr->update($data);

        if(isset($data['categories'])){
            $id_attr = $attr->id;
            $categories = $data['categories'];

            $selected_category = Category::where('attributes',$id)
                ->orWhere('attributes','like',$id.','.'%')
                ->orWhere('attributes','like','%'.','.$id)
                ->orWhere('attributes','like','%'.','.$id.','.'%')
                ->get()->lists('id')->toArray();
            //search in old category
            foreach ($selected_category as $select){
                if(!in_array($select,$categories)){
                    $c = Category::find($select);
                    $attrs = $c->attributes;
                    $attrs = explode(',',$attrs);
                    if (($key = array_search($id_attr, $attrs)) !== false) {
                        unset($attrs[$key]);
                    }
                    $attrs = implode(',',$attrs);
                    $c->attributes = $attrs;
                    $c->save();
                }
            }
            foreach ($categories as $cate){
                if(!in_array($cate,$selected_category)){
                    $c = Category::find($cate);
                    $attrs = $c->attributes;
                    if($attrs){
                        $attrs = $attrs.','.$attr->id;
                    }else{
                        $attrs = $attr->id;
                    }
                    $c->attributes = $attrs;
                    $c->save();
                }
            }
        }else{
            $selected_category = Category::where('attributes',$id)
                ->orWhere('attributes','like',$id.','.'%')
                ->orWhere('attributes','like','%'.','.$id)
                ->orWhere('attributes','like','%'.','.$id.','.'%')
                ->get()->lists('id')->toArray();

            foreach ($selected_category as $select){
                    $c = Category::find($select);
                    $attrs = $c->attributes;
                    $attrs = explode(',',$attrs);
                    if (($key = array_search( $attr->id, $attrs)) !== false) {
                        unset($attrs[$key]);
                    }
                    $attrs = implode(',',$attrs);
                    $c->attributes = $attrs;
                    $c->save();
            }
        }
        return redirect()->route('Staff::Management::category@listAttr')->with('success','Sửa thành công');
    }

    public function deleteAttr(Request $request,$id){
        $attr = AttrDynamic::findOrFail($id);
        DB::beginTransaction();
        try{
            $selected_category = Category::where('attributes',$id)
                ->orWhere('attributes','like',$id.','.'%')
                ->orWhere('attributes','like','%'.','.$id)
                ->orWhere('attributes','like','%'.','.$id.','.'%')
                ->get()->lists('id')->toArray();

            foreach ($selected_category as $select){
                $c = Category::find($select);
                $attrs = $c->attributes;
                $attrs = explode(',',$attrs);
                if (($key = array_search( $attr->id, $attrs)) !== false) {
                    unset($attrs[$key]);
                }
                $attrs = implode(',',$attrs);
                $c->attributes = $attrs;
                $c->save();
            }
            AttrValue::where('attribute_id',$attr->id)->delete();
            $attr->delete();

            DB::commit();
        }catch(Exception $ex){

            DB::rollBack();
            return redirect()->route('Staff::Management::category@listAttr')->with('error','Có lỗi! Vui lòng thử lại sau');
        }
        return redirect()->route('Staff::Management::category@listAttr')->with('success','Xóa thành công');
    }
}
