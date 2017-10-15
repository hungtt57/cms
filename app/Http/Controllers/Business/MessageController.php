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
class MessageController extends Controller
{
    public function index(Request $request){
        $business = auth()->user();
        $messages = DNMessage::where('business_id',$business->id)->paginate(10);
        return view('business.message.index',compact('messages'));
    }
    public function add(Request $request){
        return view('business.message.form');
    }
    public function store(Request $request){
        $data =$request->all();
        $this->validate($request,[
           'content' => 'required',
            'type_object_to' => 'required',
            'object_to' => 'required',
            'type_send' => 'required',
        ],[
            'content.required' => 'Vui lòng nhập nội dung tin nhắn',
            'type_object_to.required' => 'Vui lòng chọn loại đích đến',
            'object_to.required' => 'Nhập đích đến',
            'type_send.required' => 'Chọn loại gửi'
        ]);

        if(isset($data['check_product']) and $data['check_product'] == 2 and $data['list_barcode'] == null){
            return redirect()->back()->withErrors(['list_barcode' => 'Vui lòng nhập danh sách barcode'])->withInput();
        }
        $count = explode(',',$data['list_barcode']);
        if($data['check_product'] == 2 and $count <= 0){
            return redirect()->back()->withErrors(['list_barcode' => 'Barcode cách nhau bởi dấu , '])->withInput();
        }
        $business = auth()->user();
        if($data['type_send'] == 2){
            $status = DNMessage::STATUS_ORDER;
        }else{
            $status = DNMessage::STATUS_PENDING;
        }

        DNMessage::create([
            'business_id' => $business->id,
            'content' => $data['content'],
            'type_object_to' => $data['type_object_to'],
            'object_to' => $data['object_to'],
            'type_send' => $data['type_send'],
            'time_send' => $data['time_send'],
            'comment_product' => $data['comment_product'],
            'like_product' => $data['like_product'],
            'scan_product' => $data['scan_product'],
            'check_product' => $data['check_product'],
            'list_barcode' => $data['list_barcode'],
            'status' => $status
        ]);
        return redirect()->route('Business::message@index')->with('success','Tạo message thành công');

    }
    public function delete(Request $request,$id){
       $message = DNMessage::find($id);
        $message->delete();
        return redirect()->route('Business::message@index')->with('success','Xóa message thành công');
    }


}
