<?php

namespace App\Http\Controllers\Ajax\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Models\Mongo\Social\StaffNotification as Notification;
use App\Models\Enterprise\MStaffNotification as Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {

        $unreadCount = Notification::where('unread', true)->count();
        $notifications = Notification::orderBy('createdAt', 'desc');

        if ($request->has('since')) {
            $notifications = $notifications->where('createdAt', '>=', Carbon::createFromTimeStamp((int) $request->input('since')));
        } else {
            $notifications = $notifications->take((int) $request->input('limit', 10));
        }

        $notifications = $notifications->get();

        $notifications->map(function ($notification) {
            $notification->link = route('Staff::notification@read', [
                $notification->id
            ]);

            return $notification;
        });

        return ['data' => $notifications, 'metadata' => ['unreadCount' => $unreadCount]];
    }
}
