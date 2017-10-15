<?php

namespace App\Http\Controllers\Business;

use App\Models\Enterprise\ProductDistributor;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Exception\RequestException;
use Auth;
use Event;
use App\Events\BusinessProductsFileUploaded;
use Illuminate\Support\MessageBag;
use App\Models\Mongo\Product\PProduct;
use DB;
use App\Events\FileEditProductDistributor;
use App\Models\Enterprise\BusinessNotifications;
use Carbon\Carbon;
class NotificationController extends Controller
{
   public function index(Request $request){
        $notification  = BusinessNotifications::find(4);
       $business = Auth::user();

       $unreadCount = BusinessNotifications::where('unread', 1)->where('business_id',$business->id)->count();

       $notifications = BusinessNotifications::where('business_id',$business->id)->orderBy('created_at', 'desc');

       if ($request->has('since')) {
           $notifications = $notifications->where('created_at', '>=', Carbon::createFromTimeStamp((int) $request->input('since')));
       } else {
           $notifications = $notifications->take((int) $request->input('limit', 10));
       }

       $notifications = $notifications->get();

       $notifications->map(function ($notification) {
           $notification->link = route('Business::notification@read', [
               $notification->id
           ]);

           return $notification;
       });

       return ['data' => $notifications, 'metadata' => ['unreadCount' => $unreadCount]];
   }
   public function listNotification(Request $request){
       $business = Auth::user();
       $notifications = BusinessNotifications::where('business_id',$business->id)->orderBy('created_at', 'desc')->get();
       return view('business.notification.list',compact('notifications'));
   }
   public function read($id,Request $request){
       $notification = BusinessNotifications::findOrFail($id);
       $notification->unread = 0;
       $notification->save();
       $eBARCODE = null;
       $eGLN = null;
       $eImage = null;
       $eB = null;
       $eBarcodePP = null;
       $eEditPP = null;
       $barcodeSX_invalid = null;
       $eSX = null;
       $ePrice = null;
       foreach (json_decode($notification->data) as $key => $value){

            if($key=='barcode_invalid'){
                $eBARCODE = $value;
            }
            if($key == 'gln_invalid'){
                $eGLN = $value;
            }
            if($key== 'image_invalid'){
                $eImage = $value;
            }
            if($key == 'barcode_vendor'){
                $eB = $value;
            }

           if($key == 'barcodePP_invalid'){
               $eBarcodePP = $value;
           }
           if($key == 'editPP_invalid'){
               $eEditPP = $value;
           }
           if($key == 'barcodeSX_invalid'){
               $barcodeSX_invalid = $value;
           }
           if($key == 'Sx_invalid'){
               $eSX = $value;
           }
           if($key == 'price_invalid'){
               $ePrice = $value;
           }


       }

       return view('business.notification.read',compact('notification','ePrice','eBARCODE','eGLN','eImage','eB','eBarcodePP','eEditPP','barcodeSX_invalid','eSX'));
   }







}
