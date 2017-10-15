<?php

namespace App\Http\Controllers\Business;

use App\Models\Enterprise\DNMessage;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;
use Event;
use DB;
use Carbon\Carbon;
use Response;
use App\Models\Icheck\Product\ProductAttr;

class HighLightController extends Controller
{
    public function index(){
        $highLights = ProductAttr::where('is_core',0)->where('owner',Auth::user()->id)->paginate(20);
        return view('business.highlight.index',compact('highLights'));
    }
    public function add(Request $request){
        return view('business.highlight.add');

    }
    public function edit(Request $request,$id){
        $hl = ProductAttr::where('id',$id)->where('is_core',0)->where('owner',Auth::user()->id)->first();
        if(empty($hl)){
            return redirect()->back()->with('error','Không tồn tại HighLight');
        }
        return view('business.highlight.add',['highlight' => $hl]);
    }
    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required',
        ],[
            'title.required' => 'Vui lòng nhập tiêu đề'
        ]);
        $data = $request->all();
        if ($request->hasFile('icon')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('icon')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['icon'] = $res->prefix;
        }
        $data['is_core'] = 0;
        $data['owner'] = Auth::user()->id;
        ProductAttr::create($data);

        return redirect()->route('Business::highlight@index')
            ->with('success', 'Đã thêm hight light');


    }
    public function update(Request $request,$id){
        $this->validate($request,[
            'title' => 'required',
        ],[
            'title.required' => 'Vui lòng nhập tiêu đề'
        ]);
        $hl = ProductAttr::where('id',$id)->where('is_core',0)->where('owner',Auth::user()->id)->first();
        if(empty($hl)){
            return redirect()->back()->with('error','Không tồn tại HighLight');
        }
        $data = $request->all();
        if ($request->hasFile('icon')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('icon')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['icon'] = $res->prefix;
        }
        $data['is_core'] = 0;
        $data['owner'] = Auth::user()->id;
        $hl->update($data);
        return redirect()->route('Business::highlight@index')
            ->with('success', 'Sửa thành công');

    }
    public function search(Request $request){
        $key = $request->input('term');
        if($key){
            $attrs = ProductAttr::where('title','like','%'.$key.'%')->get();
                return Response::json($attrs);
        }
        return Response::json([],500);

    }

}
