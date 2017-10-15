<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;
use Event;
use DB;
use Carbon\Carbon;
use App\Models\Enterprise\DNNotificationUser;
class NotificationUserController extends Controller
{
    public function index(Request $request){

        $notifications = DNNotificationUser::select('*');
        if($request->input('status') != ''){
            $status = $request->input('status');
            $notifications = $notifications->where('status',$status);
        }
        if($request->input('content')){
            $content = $request->input('content');
            $notifications = $notifications->where('content','like','%'.$content.'%');
        }
        $notifications = $notifications->paginate(10);
        return view('staff.management.notification_user.index',compact('notifications'));
    }

//    public function delete(Request $request,$id){
//       $message = DNNotificationUser::find($id);
//        $message->delete();
//        return redirect()->route('Business::notificationUser@index')->with('success','Xóa thông báo thành công');
//    }

    public function approve(Request $request,$id){
        $notification = DNNotificationUser::find($id);

        if($notification->type_send == 2){
            $now  = Carbon::now()->getTimestamp();
            if($now >= strtotime($notification->time_send)){
                $notification->status = DNNotificationUser::STATUS_REJECT;
                $notification->note = 'Quá thời gian đặt lịch!! Vui lòng sửa lại';
                $notification->save();
                return redirect()->back()->with('success','Duyệt không thành công! vì quá thời gian đặt lịch');
            }

        }
        $notification->status = DNNotificationUser::STATUS_APPROVE;
        $notification->note = '';
        $notification->save();
        return redirect()->back()->with('success','Duyệt thành công');
    }
    public function disapprove(Request $request,$id){
        $note = $request->input('note');
        $notification = DNNotificationUser::find($id);
        $notification->note =$note;
        $notification->status = DNNotificationUser::STATUS_REJECT;
        $notification->save();
        return redirect()->back()->with('success','Không duyệt thành công!');
    }
}
