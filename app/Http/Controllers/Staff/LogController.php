<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Vendor;
use Auth;
use App\Models\Enterprise\MLog;
use App\Models\Enterprise\LogSearchVendor;
use Carbon\Carbon;
class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = MLog::orderBy('createdAt', 'desc');

        if ($request->has('search')) {
            $logs = $logs->where(function ($query) use ($request) {
                $query->where('email', $request->input('search'))
                    ->orWhere('action', 'like', '%' . $request->input('search') . '%');
            });
        }
        $logs = $logs->paginate(50);

        return view('staff.log.log', compact('logs'));
    }
    public function logSearchVendor(Request $request){
        $logs = LogSearchVendor::orderBy('createdAt', 'desc');

        if ($request->has('search')) {
            $logs = $logs->where(function ($query) use($request) {
                $query->where('email', $request->input('search'));
                $query->orWhere('key', $request->input('search'));
            });
        }

        if ($createdAtFrom = $request->input('created_at_from')) {
            $logs = $logs->where('createdAt', '>=', Carbon::createFromFormat('Y-m-d', $createdAtFrom)->startOfDay());
        }

        if ($createdAtTo = $request->input('created_at_to')) {
            $logs = $logs->where('createdAt', '<=', Carbon::createFromFormat('Y-m-d', $createdAtTo)->endOfDay());
        }

        $logs = $logs->paginate(50);

        return view('staff.log.log_search_vendor', compact('logs'));
    }



}
