<?php


namespace App\Http\Controllers\Staff\Management;


use App\Models\Enterprise\TopHotProduct;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;


class TopHotProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')){
            $top_hot_products = TopHotProduct::where('gtin', 'like', '%' . $request->input('search') . '%')->paginate(15);
        }
        else{
            $top_hot_products = TopHotProduct::paginate(15);
        }

        return view('staff.management.top_hot_product.index',compact('top_hot_products'));

    }

    public function add()
    {
        return view('staff.management.top_hot_product.form');
    }

    public function store(Request $request)
    {
        $this->validate($request,[

            'gtin'=>'required',
            'order'=>'required',
        ]);

        $data = $request->all();
        $top_hot_product = TopHotProduct::create($data);
        $top_hot_product->save();

        return redirect()->route('Staff::Management::top_hot_product@index')
            ->with('success', 'Đã thêm');
    }

    public function edit($id)
    {
        $top_hot_product = TopHotProduct::findOrFail($id);
        return view('staff.management.top_hot_product.form',compact('top_hot_product'));
    }

    public function update($id,Request $request)
    {
        $this->validate($request,[
            'gtin'=>'required',
            'order'=>'required',
        ]);

        $top_hot_product = TopHotProduct::findOrFail($id);
        $data = $request->all();

        $top_hot_product->update($data);


        return redirect()->route('Staff::Management::top_hot_product@index',$top_hot_product->id)
            ->with('success', 'Đã cập nhật');
    }



    public function delete($id)
    {
        $top_hot_product = TopHotProduct::findOrFail($id);
        $top_hot_product->delete();
        return redirect()->route('Staff::Management::top_hot_product@index')->with('success', 'Đã xoá thành công');
    }


}