<?php


namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Icheck\Product\Message;
use App\Models\Icheck\Product\ProductMessage;
use Cache;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\Country;
//use App\Models\Icheck\Product\vendorProduct;
use App\Models\BarcodeViet\MSMVGTIN;
use Illuminate\Database\QueryException;
class VendorController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-vendor')) {
            abort(403);
        }

        if ($request->input('search')){
            $vendors = Vendor::orderBy('updatedAt','desc')->where('gln_code', 'like', '%' . $request->input('search') . '%')->paginate(8);
        }
        else{
            $vendors = Vendor::orderBy('updatedAt','desc')->paginate(8);
        }
        return view('staff.management.vendor.index',compact('vendors'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-vendor')) {
            abort(403);
        }

        $messages = Message::all();
        $countries = Country::all();

        return view('staff.management.vendor.form',compact('countries','messages'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-vendor')) {
            abort(403);
        }

        $this->validate($request, [
            'gln_code' => 'required|unique:icheck_product.vendor,gln_code',
            'name' =>'required',
            'address' =>'required',
        ]);

        $data = $request->all();
        $data['internal_code'] = 'iv_'.microtime(true) * 10000;
        $data['name'] = Vendor::encrypt($data['name']);
        $data['address'] = Vendor::encrypt($data['address']);

        $vendor = Vendor::create($data);
        $vendor->country()->associate($request->input('country'));
        $vendor->save();

        if (isset($data['warning_id'])) {
            if($data['warning_id']){
                ProductMessage::create([
                    'gln_code' => $vendor->gln_code,
                    //'gln_code' => @$product->gln->gln,
                    'message_id' => $data['warning_id'],
                ]);
            }

        } else {
            ProductMessage::where('gln_code', $vendor->gln_code)->delete();
        }

        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Thêm GLN ' . $vendor->name . '(' . $vendor->gln_code  . ')',
        ]);

        return redirect()->route('Staff::Management::vendor@index')
            ->with('success', 'Đã thêm vendor');

    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-vendor')) {
            abort(403);
        }
        $messages = Message::all();
        $vendor = Vendor::findOrFail($id);
        $countries = Country::all();
        $warning = ProductMessage::where('gln_code', $vendor->gln_code)->first();

        return view('staff.management.vendor.form',compact('vendor','countries','messages','warning'));
    }

    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-vendor')) {
            abort(403);
        }
        $vendor = Vendor::findOrFail($id);

        $this->validate($request, [
            'gln_code' => 'required|unique:icheck_product.vendor,gln_code,' . $vendor->id,
            'name' =>'required',
            'address' =>'required',
        ]);
        $data = $request->all();
        try{
            $data['name'] = Vendor::encrypt($data['name']);
            $data['address'] = Vendor::encrypt($data['address']);
            $vendor->update($data);

            $vendor->country()->associate($request->input('country'));

            if ($data['warning_id']) {
                ProductMessage::where('gln_code', $vendor->gln_code)->delete();
                if($data['warning_id']){
                    ProductMessage::create([
                        'gln_code' => $vendor->gln_code,
                        'message_id' => $data['warning_id'],
                    ]);
                }
            } else {
                ProductMessage::where('gln_code', $vendor->gln_code)->delete();
            }
            $vendor->save();
        }catch(\Exception $ex){

            return redirect()->route('Staff::Management::vendor@index')
                ->with('success', 'Có lỗi xảy ra.Vui lòng thử lại sau');
        }


        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Sửa GLN ' . $vendor->name . '(' . $vendor->gln_code  . ')',
        ]);


        return redirect()->route('Staff::Management::vendor@index')
            ->with('success', 'Đã cập nhật vendor');
    }


    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-vendor')) {
            abort(403);
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Xoá GLN ' . $vendor->name . '(' . $vendor->gln_code  . ')',
        ]);

        return redirect()->route('Staff::Management::vendor@index')->with('success', 'Đã xoá thành công');;
    }

    public function vendorInline($id, Request $request)
    {

        $vendor = Vendor::findOrFail($id);

        if ($request->input('name')) {
            $vendor->name = Vendor::encrypt($request->input('name'));
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa vendor ' . $vendor->name . '(' . $id . ')',
            ]);
        }

        if ($request->input('address')) {
            $vendor->address = Vendor::encrypt($request->input('address'));
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa vendor ' . $vendor->address . '(' . $id . ')',
            ]);
        }

        if ($request->input('phone')) {
            $vendor->phone = $request->input('phone');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa vendor ' . $vendor->phone . '(' . $id . ')',
            ]);
        }

        if ($request->input('email')) {
            $vendor->email = $request->input('email');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa vendor ' . $vendor->email . '(' . $id . ')',
            ]);
        }

        if ($request->input('website')) {
            $vendor->website = $request->input('website');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa vendor ' . $vendor->website . '(' . $id . ')',
            ]);
        }
        if ($request->has('prefix')) {
            $vendor->prefix = $request->input('prefix');
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Sửa vendor prefix' . $vendor->website . '(' . $id . ')',
            ]);
        }

        $vendor->save();

        return 'oke';
    }

//    private function updateBarcodeViet($vendor){
//        try{
//            if(MSMVGTIN::where('gln_code', $vendor->gln_code)->count()){
//                MSMVGTIN::where('gln_code', $vendor->gln_code)->update(
//                    ['company_name' => @$vendor->name,
//                        'company_address' => @$vendor->address,
//                        'company_contact' => @$vendor->phone,
//                    ]);
//            }
//        } catch(\Exception $ex) {
//            throw $ex;
//        }
//
//
//    }
}
