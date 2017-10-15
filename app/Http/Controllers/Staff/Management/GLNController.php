<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\Product as EProduct;
use App\Models\Enterprise\ProductCategory;

use App\Models\Icheck\Product\Country;

use App\Models\Icheck\Product\ProductInfo;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Icheck\Product\Product;
use App\Models\Icheck\Product\Vendor;
use Auth;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use App\Jobs\AutoSetRelateProductApproveGLN;
use App\Models\Icheck\Product\VendorStatistic;
class GLNController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-gln')) {
            abort(403);
        }

        if($request->input('status') != null && $request->input('status')!= 1994){
            $status = $request->input('status');
            $gln = GLN::orderBy('created_at', 'desc')->where('status',$status)->get();
        }else{
            $gln = GLN::orderBy('created_at', 'desc')->get();
        }


        return view('staff.management.gln.index', compact('gln'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-gln')) {
            abort(403);
        }
        $businesses = Business::all();
        $countries = Country::all();

        return view('staff.management.gln.form', compact('businesses', 'countries'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-gln')) {
            abort(403);
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'gln' => 'required|unique:gln,gln,NULL,id,status,' . GLN::STATUS_APPROVED,
            'business_id' => 'required|exists:businesses,id',
            'country_id' => 'required|exists:icheck_product.country,id',
            'address' => 'required',
            'prefix' => 'max:13'
        ]);

        $data = $request->all();
        $gln = GLN::create($data);
        $gln->business()->associate($request->input('business_id'));
        $gln->country()->associate($request->input('country_id'));
        $gln->status = GLN::STATUS_PENDING_ACTIVATION;
        $gln->save();

        return redirect()->route('Staff::Management::gln@index')
            ->with('success', 'Đã thêm GLN' . $gln->gln);
    }

    public function viewCertificateFile($file)
    {
        if (!preg_match('/^[a-z0-9]+_[a-z0-9]{32}\.[a-z0-9]+$/', $file)) {
            abort(404);
        }

        $file = storage_path('app/certificate_files/' . $file);

        if (!file_exists($file)) {
            abort(404);
        }

        return response()->file($file);
    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-gln')) {
            abort(403);
        }
        $gln = GLN::findOrFail($id);
        $businesses = Business::all();
        $countries = Country::all();

        return view('staff.management.gln.form', compact('gln', 'businesses', 'countries'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-gln')) {
            abort(403);
        }
        $gln = GLN::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'business_id' => 'required|exists:businesses,id',
            'country_id' => 'required|exists:icheck_product.country,id',
            'address' => 'required',
            'prefix' => 'max:13'
        ]);

        $data = $request->all();

        $gln->update($data);
        $gln->business()->associate($request->input('business_id'));
        $gln->country()->associate($request->input('country_id'));
        $gln->save();

        return redirect()->route('Staff::Management::gln@index')
            ->with('success', 'Đã cập nhật thông tin Mã địa điểm toàn cầu');
    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-gln')) {
            abort(403);
        }
        $gln = GLN::findOrFail($id);
        $vendorStatistic = VendorStatistic::where('gln_code',$gln->gln)->first();
        if($vendorStatistic){
            $vendorStatistic->signed = 0;
            $vendorStatistic->save();
        }
        $gln->delete();

        return redirect()->back()->with('success', 'Xoá Nhà sản xuất thành công');
    }

    public function approve($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('approve-gln')) {
            abort(403);
        }
        $gln = GLN::findOrFail($id);

        if (!is_null(GLN::where('gln', $gln->gln)->where('status', GLN::STATUS_APPROVED)->first())) {
            return redirect()->back()
                ->withErrors(['gln' => 'Mã địa điểm toàn cầu (GLN) ' . $gln->gln . ' đã được đăng ký bởi một doanh nghiệp khác.'])
                ->withInput()
            ;
        }

        $v = Vendor::where('gln_code',$gln->gln)->first();

        $data = [
            'name' => $gln->name,
            'address' => $gln->address,
            'country' => $gln->country->id,
            'email' => $gln->email,
            'phone' => $gln->phone_number,
        ];
        if(empty($v)){
            $data['gln_code'] = $gln->gln;
            $data['internal_code'] = 'iv_'.microtime(true) * 10000;
            $data['name'] = Vendor::encrypt($data['name']);
            $data['address'] = Vendor::encrypt($data['address']);
            $vendor = Vendor::create($data);
            $vendor->country()->associate($gln->country->id);
            $vendor->save();
            $v = $vendor;
        }
        $vendorStatistic = VendorStatistic::where('gln_code',$gln->gln)->first();
        if(empty($vendorStatistic)){
            $vendorStatistic = VendorStatistic::firstOrCreate(['gln_code' => $gln->gln]);
            $vendorStatistic->name = $v->name;
            $vendorStatistic->signed = 1;
            $vendorStatistic->save();
        }else{
            $vendorStatistic->signed = 1;
            $vendorStatistic->save();
        }





        $gln->status = GLN::STATUS_APPROVED;
        $gln->save();
        $products = $v->products()->where('status',Product::STATUS_APPROVED)->get();
        $attrs = ProductAttr::lists('id')->toArray();
        if($products){
            $this->dispatch(new AutoSetRelateProductApproveGLN($products, $gln,$v));
        }

        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Approve GLN ' . $gln->name . '(' . $gln->gln  . ')',
        ]);
        return redirect()->back()
            ->with('success', 'GLN ' . $gln->gln . ' đã được chấp nhận');


    }
}
