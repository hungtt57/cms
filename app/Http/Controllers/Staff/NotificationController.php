<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\MStaffNotification as Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::orderBy('createdAt', 'desc');
        $notifications = $notifications->get();

        $notifications->map(function ($notification) {
            $notification->link = route('Staff::notification@read', [
                $notification->id
            ]);

            return $notification;
        });

        return view('staff.notification.index', compact('notifications'));
    }

    public function read($id, Request $request)
    {
        $notification = Notification::findOrFail($id);
        $notification->unread = false;
        $notification->save();

        switch ($notification->type) {
            case Notification::TYPE_BUSINESS_REGISTERED:
                return redirect()->route('Staff::Management::business@show', [
                    $notification->data['business']['id']
                ]);

                break;

            case Notification::TYPE_BUSINESS_ADD_GLN:
            case Notification::TYPE_BUSINESS_UPDATE_GLN:
            case Notification::TYPE_BUSINESS_DELETE_GLN:
                return redirect()->route('Staff::Management::gln@edit', [
                    $notification->data['gln']['id']
                ]);

                break;

            case Notification::TYPE_BUSINESS_ADD_PRODUCT:
            case Notification::TYPE_BUSINESS_UPDATE_PRODUCT:
            case Notification::TYPE_BUSINESS_DELETE_PRODUCT:
                return redirect()->route('Staff::Management::product@edit', [
                    $notification->data['product']['id']
                ]);

                break;

            case Notification::TYPE_IMPORT_PRODUCT_FAILED:
                $html = '';
                $html .= "gtin_invalid: <textarea rows='20' cols='50'>" . implode("\n", $notification->data['gtin_invalid']) . "</textarea><br />";
                $html .= "vendor_invalid: <textarea rows='20' cols='50'>" . implode("\n", $notification->data['vendor_invalid']) . "</textarea><br />";
                $html .= "info_e: <textarea rows='20' cols='50'>" . implode("\n", $notification->data['info_e']) . "</textarea><br />";

                return $html;

                break;
            case Notification::TYPE_BUSINESS_EDIT_PRODUCTPP_FILE:
                return redirect()->route('Staff::Management::businessDistributor@listEditProductDistributor');
            case Notification::TYPE_BUSINESS_ADD_PRODUCT_FILE:
                return redirect()->route('Staff::Management::product@index');
            default:
                # code...
                break;
        }
    }
    public function readAll(Request $request){
        Notification::where('unread',true)->update(['unread' => false]);
        return redirect()->back();
    }
}
