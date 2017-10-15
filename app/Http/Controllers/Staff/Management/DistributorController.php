<?php


namespace App\Http\Controllers\Staff\Management;

use App\Models\Icheck\Product\DistributorTitle;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Models\Social\Distributor;
use Auth;
//use App\Models\Social\Country;

use App\Models\Icheck\Product\Distributor;
use App\Models\Icheck\Product\Country;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-distributor')) {
            abort(403);
        }


        $distributors = Distributor::orderBy('createdAt', 'desc');

        if ($request->input('search')) {
            $distributors = $distributors->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $distributors = $distributors->paginate(8);
        return view('staff.management.distributor.index',compact('distributors'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-distributor')) {
            abort(403);
        }
        $countries = Country::all();
        $titles = DistributorTitle::all();
        return view('staff.management.distributor.form',compact('countries','titles'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-distributor')) {
            abort(403);
        }
        $this->validate($request, [
            'name' =>'required',
        ]);

        $data = $request->all();

        $distributor = Distributor::create($data);
        $distributor->country()->associate($request->input('country'));
        $distributor->save();

        return redirect()->route('Staff::Management::distributor@index')
            ->with('success', 'Đã thêm tin tức');

    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-distributor')) {
            abort(403);
        }
        $distributor = Distributor::findOrFail($id);
        $countries = Country::all();
        $titles = DistributorTitle::all();
        return view('staff.management.distributor.form',compact('distributor','countries','titles'));
    }

    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-distributor')) {
            abort(403);
        }
        $this->validate($request, [
            'name' =>'required',
        ]);
        $distributor = Distributor::findOrFail($id);
        $data = $request->all();

        $distributor->update($data);
        $distributor->country()->associate($request->input('country'));
        $distributor->save();
        return redirect()->route('Staff::Management::distributor@index',$distributor->id)
            ->with('success', 'Đã cập nhật tin tức');
    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-distributor')) {
            abort(403);
        }
        $distributor = Distributor::findOrFail($id);
        $distributor->delete();
        return redirect()->route('Staff::Management::distributor@index')->with('success', 'Đã xoá thành công');;
    }

    public function distributorInline($id, Request $request)
    {

        $distributor = Distributor::find($id);

        if ($request->input('name')) {
            $distributor->name = $request->input('name');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa Distributor ' . $distributor->name . '(' . $id . ')',
            ]);
        }

        if ($request->input('address')) {
            $distributor->address = $request->input('address');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa Distributor ' . $distributor->address . '(' . $id . ')',
            ]);
        }

        if ($request->input('contact')) {
            $distributor->contact = $request->input('contact');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa Distributor ' . $distributor->contact . '(' . $id . ')',
            ]);
        }

        if ($request->input('other')) {
            $distributor->other = $request->input('other');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa Distributor ' . $distributor->other . '(' . $id . ')',
            ]);
        }

        $distributor->save();

        return 'oke';
    }
}
