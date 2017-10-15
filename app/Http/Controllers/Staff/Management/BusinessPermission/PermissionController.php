<?php


namespace App\Http\Controllers\Staff\Management\BusinessPermission;

use App\Models\Enterprise\Bpermission as Permission;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;


class PermissionController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-business-permission')) {
            abort(403);
        }

        $permissions = Permission::paginate(15);

        return view('staff.management.business_permission.permission.index',compact('permissions'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-business-permission')) {
            abort(403);
        }

        return view ('staff.management.business_permission.permission.form');
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-business-permission')) {
            abort(403);
        }

        $this->validate($request,[
            'id'=>'required',
            'description'=>'required',
        ]);

        $data = $request->all();
        $permission = Permission::create($data);
        $permission->save();

        return redirect()->route('Staff::Management::businessPermission@permission@index')
            ->with('success', 'Đã thêm');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business-permission')) {
        abort(403);
    }

        $permission = Permission::findOrFail($id);
        return view('staff.management.business_permission.permission.form',compact('permission'));
    }

    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-business-permission')) {
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
        return redirect()->route('Staff::Management::businessPermission@permission@index',$permission->id)
            ->with('success', 'Đã cập nhật');
    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-business-permission')) {
            abort(403);
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('Staff::Management::businessPermission@permission@index')->with('success', 'Đã xoá thành công');
    }



}
