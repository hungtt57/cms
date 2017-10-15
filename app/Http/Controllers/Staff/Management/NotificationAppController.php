<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\Permission;
use App\Models\Enterprise\Role;
use App\Models\Enterprise\UserPermission;
use App\Models\Enterprise\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Models\Enterprise\User;
use App\Http\Controllers\Controller;
use App\Models\Mongo\Social\Notification;
class NotificationAppController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-notificationapp')) {
            abort(403);
        }


        return view('staff.management.notificationapp.index');
    }

//    public function add()
//    {
//        if (auth()->guard('staff')->user()->cannot('add-fakeuser')) {
//            abort(403);
//        }
//        return view('staff.management.fakeuser.form');
//    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-notificationapp')) {
            abort(403);
        }

        $this->validate($request,[
            'message' =>'required',

        ]);
        $message = $request->input('message');
        $notification = new Notification();
        $notification->icheck_id = 'all';
        $notification->data = ['message'=>$message];
        $notification->object_type = Notification::OBJECT_TYPE_POST;
        $notification->save();


        return redirect()->route('Staff::Management::notificationapp@index')
            ->with('success', 'Đã thêm Notification');
    }

//    public function edit($id)
//    {
//        if (auth()->guard('staff')->user()->cannot('edit-user')) {
//            abort(403);
//        }
//
//        $user = User::findOrFail($id);
//        $roles = Role::all();
//        $userRoles = $user->roles->keyBy('id');
//        // ['id' => ['id' => '', 'name' => ''], ...]
//        $userPermissions = $user->permissions->keyBy('id');
//        $permission = Permission::all();
//        return view('staff.management.user.form', compact('user','roles', 'userRoles','permission','userPermissions'));
//    }
//
//    public function update($id, Request $request)
//    {
//        if (auth()->guard('staff')->user()->cannot('edit-user')) {
//            abort(403);
//        }
//
//        $user = User::findOrFail($id);
//
//        $this->validate($request, [
//            'name' => 'required|max:255',
//            'email' => 'required|email',
//            'password' => 'min:6|confirmed',
//        ]);
//
//        $data = $request->all();
//        if (!$request->input('password')) {
//            unset($data['password']);
//        } else {
//            $data['password'] = bcrypt($data['password']);
//        }
//
//        $user->roles()->sync($request->input('role',[]));
//        $permissions = [];
//
//
//        foreach($request->input('status',[]) as $permissionId => $value) {
//
//            $permissions[$permissionId] = ['value' => $value];
//
//        }
//
//        $user->permissions()->sync($permissions);
//
//
//        $user->update($data);
//
//        return redirect()->route('Staff::Management::user@edit', $user->id)
//            ->with('success', 'Đã cập nhật thông tin Nhà sản xuất');
//
//    }
//
//    public function delete($id)
//    {
//        if (auth()->guard('staff')->user()->cannot('delete--fakeuser')) {
//            abort(403);
//        }
//
//        $user = Account::findOrFail($id);
//        $user->delete();
//        return redirect()->route('Staff::Management::fake@index')->with('success', 'Đã xoá thành công');
//    }

}
