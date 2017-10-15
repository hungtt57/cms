<?php


namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\Permission;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;


class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-permission')) {
            abort(403);
        }
        $permissions = Permission::latest('updated_at');
        if($request->input('search')){
            $permissions = $permissions->where('id','like','%'.$request->input('search').'%')->orWhere('description','like','%'.$request->input('search').'%');
        }
        $permissions = $permissions->paginate(15);

        return view('staff.management.permission.index',compact('permissions'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-permission')) {
            abort(403);
        }

        return view ('staff.management.permission.form');
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-permission')) {
            abort(403);
        }

        $this->validate($request,[
            'id'=>'required',
            'description'=>'required',
        ]);

        $data = $request->all();
        $permission = Permission::create($data);
        $permission->save();

        return redirect()->route('Staff::Management::permission@index')
            ->with('success', 'Đã thêm');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-permission')) {
            abort(403);
        }

        $permission = Permission::findOrFail($id);
        return view('staff.management.permission.form',compact('permission'));
    }

    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-permission')) {
            abort(403);
        }

        $this->validate($request, [
            'id' =>'required',
            'description' =>'required',

        ]);
        $permission = Permission::findOrFail($id);
        $data = $request->all();

        $permission->update($data);
        $permission->save();
        return redirect()->route('Staff::Management::permission@index',$permission->id)
            ->with('success', 'Đã cập nhật');
    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-permission')) {
            abort(403);
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('Staff::Management::permission@index')->with('success', 'Đã xoá thành công');
    }



}
