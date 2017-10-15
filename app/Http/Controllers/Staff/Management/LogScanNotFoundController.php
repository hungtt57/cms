<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Icheck\Product\Country;

use Auth;
use App\Models\Mongo\User\LogScanNotFound;
class LogScanNotFoundController extends Controller
{
    public function index(Request $request)
    {
        $logs = LogScanNotFound::orderBy('score','desc');
        if($request->input('code')){
            $logs=$logs->where('_id','like','%'.$request->input('code').'%');
        }
        $logs = $logs->paginate(10);
        return view('staff.management.logscannotfound.index',compact('logs'));
    }

    public function add()
    {

    }


}
