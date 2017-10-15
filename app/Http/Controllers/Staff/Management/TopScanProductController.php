<?php


namespace App\Http\Controllers\Staff\Management;


use App\Models\Enterprise\TopScanProduct;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;


class TopScanProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')){
            $top_scan_products = TopScanProduct::where('gtin', 'like', '%' . $request->input('search') . '%')->paginate(15);
        }
        else{
            $top_scan_products = TopScanProduct::paginate(15);
        }

        return view('staff.management.top_scan_product.index',compact('top_scan_products'));
    }

    public function add()
    {
        return view('staff.management.top_scan_product.form');
    }

    public function store(Request $request)
    {
        $this->validate($request,[

            'gtin'=>'required',
            'order'=>'required',
        ]);

        $data = $request->all();
        $top_scan_product = TopScanProduct::create($data);
        $top_scan_product->save();

        return redirect()->route('Staff::Management::top_scan_product@index')
            ->with('success', 'Đã thêm');
    }

    public function edit($id)
    {
        $top_scan_product = TopScanProduct::findOrFail($id);
        return view('staff.management.top_scan_product.form',compact('top_scan_product'));
    }

    public function update($id,Request $request)
    {
        $this->validate($request,[
            'gtin'=>'required',
            'order'=>'required',
        ]);

        $top_scan_product = TopScanProduct::findOrFail($id);
        $data = $request->all();

        $top_scan_product->update($data);


        return redirect()->route('Staff::Management::top_scan_product@index',$top_scan_product->id)
            ->with('success', 'Đã cập nhật');
    }



    public function delete($id)
    {
        $top_scan_product = TopScanProduct::findOrFail($id);
        $top_scan_product->delete();
        return redirect()->route('Staff::Management::top_scan_product@index')->with('success', 'Đã xoá thành công');
    }


}