<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Vendor;
use App\Models\Enterprise\Product;
use Auth;
use App\GALib\AnalyticsLib;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\Enterprise\Job;
use App\Models\Enterprise\FailedJobs;
use Illuminate\Support\Facades\Artisan;
class DashboardController extends Controller
{
    public function index()
    {
        return view('staff.dashboard');
    }

    public function getTokenFireBase(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('chat')) {
            abort(403);
        }
        $icheck_id = 'i-1469083968959';

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('GET', env('DOMAIN_API') . 'users/' . $icheck_id . '/firebase-token', [
                'auth' => [env('USER_API'), env('PASS_API')],
            ]);
            $res = json_decode((string)$res->getBody());
        } catch (RequestException $e) {
            return $e->getResponse();
        }

        if ($res->status == 200) {
            $token = $res->data;
            return $token;
        }

    }
    public function viewJob(){
        $jobs = Job::orderBy('created_at','asc')->paginate(50, ['*'], 'page_jobs');
        $failed_jobs = FailedJobs::orderBy('id','desc')->paginate(50, ['*'], 'page_failed_jobs');
        return view('staff.view_job',compact('failed_jobs','jobs'));
    }
    public function retryJob(Request $request ,$id){

        $failed = FailedJobs::find($id);
        if($failed){
            Artisan::call('queue:retry', ['id' => [$id]]);
            return redirect()->back()->with('success','Retry job thành công!!')->with('tab-view-job', 'failjob');
        }else{
            return redirect()->back()->with('error','Không tồn tại job!!')->with('tab-view-job', 'failjob');
        }


    }
    public function deleteJob(Request $request , $id){
        $failed = FailedJobs::find($id);
        if($failed){
            $failed->delete();
            return redirect()->back()->with('success','Delete job thành công!!')->with('tab-view-job', 'failjob');
        }
        return redirect()->back()->with('error','Không tồn tại job!!')->with('tab-view-job', 'failjob');
    }

}
