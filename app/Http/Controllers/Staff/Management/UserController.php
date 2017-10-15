<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\Permission;
use App\Models\Enterprise\Role;
use App\Models\Enterprise\UserPermission;
use App\Models\Enterprise\UserRole;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Enterprise\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-user')) {
            abort(403);
        }
        $users = User::latest('created_at');
        if($request->input('search')){
            $users = User::where('name','like','%'.$request->input('search').'%');
        }
        $users = $users->paginate(8);

        return view('staff.management.user.index',compact('users'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-user')) {
            abort(403);
        }

        $roles = Role::all();
        $permission = Permission::all();
        return view('staff.management.user.form',compact('roles','permission'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-user')) {
            abort(403);
        }

        $this->validate($request,[
            'name' =>'required|max:255',
            'email' =>'required|email',

        ]);
        $data = $request->all();
        if (!($password = $request->input('password'))) {
            $password = str_random(12);
        }

        $data['password'] = bcrypt($password);
        $user = User::create($data);
        $user->roles()->sync($request->input('role',[]));
        $permissions = [];


        foreach($request->input('status',[]) as $permissionId => $value) {

            $permissions[$permissionId] = ['value' => $value];

        }

        $user->permissions()->sync($permissions);


        $user->save();
        return redirect()->route('Staff::Management::user@index')
            ->with('success', 'Đã thêm Thanh vien');
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-user')) {
            abort(403);
        }

        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->keyBy('id');
        // ['id' => ['id' => '', 'name' => ''], ...]
        $userPermissions = $user->permissions->keyBy('id');
        $permission = Permission::all();
        return view('staff.management.user.form', compact('user','roles', 'userRoles','permission','userPermissions'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-user')) {
            abort(403);
        }

        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'min:6|confirmed',
        ]);

        $data = $request->all();
        if (!$request->input('password')) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->roles()->sync($request->input('role',[]));
        $permissions = [];


        foreach($request->input('status',[]) as $permissionId => $value) {

            $permissions[$permissionId] = ['value' => $value];

        }

        $user->permissions()->sync($permissions);


        $user->update($data);
        return redirect()->route('Staff::Management::user@edit', $user->id)
            ->with('success', 'Đã cập nhật thông tin Nhà sản xuất');

    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-user')) {
            abort(403);
        }

        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('Staff::Management::user@index')->with('success', 'Đã xoá thành công');
    }

}
