<?php

namespace App\Http\Controllers\Business;

use App\Models\Enterprise\DNNotificationUser;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;
use Event;
use DB;
use Carbon\Carbon;
class NotificationUserController extends Controller
{
    public function index(Request $request){
        $business = auth()->user();
        $notifications = DNNotificationUser::where('business_id',$business->id);
        if($request->input('status') != ''){
            $status = $request->input('status');
            $notifications = $notifications->where('status',$status);
        }
        if($request->input('content')){
            $content = $request->input('content');
            $notifications = $notifications->where('content','like','%'.$content.'%');
        }
        $notifications=$notifications->paginate(10);
        return view('business.notification_user.index',compact('notifications'));
    }
    public function add(Request $request){
        return view('business.notification_user.form');
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
        $status = DNNotificationUser::STATUS_PENDING;


        DNNotificationUser::create([
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
            'list_barcode' => (isset($data['list_barcode'])) ? $data['list_barcode'] : null,
            'status' => $status
        ]);
        return redirect()->route('Business::notificationUser@index')->with('success','Tạo thông báo thành công');

    }
    public function delete(Request $request,$id){
       $message = DNNotificationUser::find($id);
        $message->delete();
        return redirect()->route('Business::notificationUser@index')->with('success','Xóa thông báo thành công');
    }
    public function edit(Request $request,$id){
        $notification = DNNotificationUser::find($id);
        return view('business.notification_user.form',compact('notification'));
    }
    public function update(Request $request,$id){
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
        $status = DNNotificationUser::STATUS_PENDING;

        $notification = DNNotificationUser::find($id);
        $notification->update([
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
            'list_barcode' => (isset($data['list_barcode'])) ? $data['list_barcode'] : null,
            'status' => $status
        ]);
        return redirect()->route('Business::notificationUser@index')->with('success','Sửa thông báo thành công');
    }

}
