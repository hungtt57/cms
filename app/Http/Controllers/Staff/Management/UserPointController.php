<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\Business;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use GuzzleHttp\Client;
use Response;
use App\Models\Icheck\User\UserPointHistory;
use Carbon\Carbon;
use App\Models\Icheck\User\Account;
class UserPointController extends Controller
{
    public function index(Request $request){
        if (auth()->guard('staff')->user()->cannot('user-point')) {
            abort(403);
        }
        if (!$request->has('date_range')) {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

            $user_points =  \DB::connection('icheck_user')->table('user_point_history')
                ->select(\DB::raw('SUM(point) as point, date_format(createdAt, "%d-%m-%Y") as date'))
                ->where('createdAt','<=',$endDate)->where('createdAt','>=',$startDate)
                ->groupBy(\DB::raw('date_format(createdAt, "%d-%m-%Y")'))->orderBy('createdAt','desc');

        $datas = clone $user_points;
        $datas = $datas->get();
        $user_points = $user_points->paginate(10);

        $chart = [];
        if($datas){
            foreach($datas as $user_point){
                $chart[] = [intval(strtotime($user_point->date)) * 1000, (float) $user_point->point];
            }
        }
        $total_point = \DB::connection('icheck_user')->table('user_point_history')
            ->select(\DB::raw('SUM(point) as point,icheck_id'))
            ->where('createdAt','<=',$endDate)->where('createdAt','>=',$startDate)->first();

        $total_user = \DB::connection('icheck_user')->table('user_point_history')
            ->select(\DB::raw('count(*) as count,icheck_id'))
            ->where('createdAt','<=',$endDate)->where('createdAt','>=',$startDate)->groupBy('icheck_id')->get();
        return view('staff.management.user_point.index',compact('startDate','endDate','chart','user_points','total_point','total_user'));
    }

        public function detailDay(Request $request,$day){
            if (auth()->guard('staff')->user()->cannot('user-point-day')) {
                abort(403);
            }
            $startDate = Carbon::parse($day)->startOfDay();
            $endDate = Carbon::parse($day)->endOfDay();


            $point_days = UserPointHistory::where('createdAt','<=',$endDate)->where('createdAt','>=',$startDate);
            if($request->input('search')){
                $key = $request->input('search');
                $point_days = $point_days->whereHas('account', function ($query) use ($key) {
                    $query->orWhere('name','like', '%'.$key.'%');
                })->orWhere('icheck_id','like','%'.$key.'%');
            }

            $point_days = $point_days->select(\DB::raw('SUM(point) as `point`,`icheck_id`'))->groupBy('icheck_id');

            $point_days = $point_days->orderBy('createdAt','desc')->paginate(20);
            $count = $point_days->total();
            return view('staff.management.user_point.detail_day',compact('point_days','day','count'));
        }

    public function statisticalByUser(Request $request){
        if (auth()->guard('staff')->user()->cannot('statistical-by-user')) {
            abort(403);
        }
        $users = UserPointHistory::select(\DB::raw('SUM(point) as `point`,`source`,COUNT(*) as count,`icheck_id`'))
            ->groupBy('icheck_id');

        if($request->input('icheck_id')){
            $users = $users->where('icheck_id','like','%'.$request->input('icheck_id').'%');

        }
        if($request->input('name')){

            $users = $users->whereHas('account', function ($query) use ($request) {
                $query->where('name','like', '%'.$request->input('name').'%');
            });

        }
        if($request->input('source')){
            $users = $users->where('source','like','%'.$request->input('source').'%');
        }
        if ($request->has('point')) {
            $point = trim($request->input('point'));

            if (substr($point, 0, 1) != '='
                and substr($point, 0, 1) != '<'
                and substr($point, 0, 1) != '>'
                and substr($point, 0, 2) != '<>'
                and substr($point, 0, 2) != '>='
                and substr($point, 0, 2) != '<='
            ) {
                $point = (int) trim($point);
                $op = '=';
            } elseif (substr($point, 0, 1) != '='
                or substr($point, 0, 1) != '<'
                or substr($point, 0, 1) != '>'
            ) {
                $op = substr($point, 0, 1);
                $point = (int) trim(substr($point, 1));
            } else {
                $op = substr($point, 0, 2);
                $point = (int) trim(substr($point, 2));
            }

            $users = $users->where('point',  $op ,$point);
            $users = $users->havingRaw('SUM(point) '.$op.$point);
        }

        if($request->input('sort_by') and $request->input('order')){
            $users  = $users->orderBy($request->input('sort_by'), $request->input('order', 'asc'));
        }

        $users = $users->paginate(20);
        $top50 = UserPointHistory::select(\DB::raw('SUM(point) as point,`source`,COUNT(*) as count,icheck_id'))
            ->groupBy('icheck_id')->orderBy('point','desc')->limit(50)->get();
        return view('staff.management.user_point.statistical_by_user',compact('users','top50'));
    }

    public function historyPoint(Request $request,$icheck_id){
         if (auth()->guard('staff')->user()->cannot('user-point-history-point')) {
             abort(403);
         }

        $histories = UserPointHistory::where('icheck_id',$icheck_id);
        if($request->input('source')){
            $source = $request->input('source');
            $histories = $histories->where('source','like','%'.$source.'%');
        }

         $histories = $histories->orderBy('createdAt','desc')->paginate(20);
        return view('staff.management.user_point.history_point',compact('histories'));
    }


    public function updatePoint(Request $request){
        if (auth()->guard('staff')->user()->cannot('user-point-update-point')) {
            abort(403);
        }
        $icheck_id = $request->input('update_icheck_id');
        $point = $request->input('point_achieved');
        if($point == 0){
            return redirect()->back()->with('error','Vui lòng nhập point lớn hơn 0');
        }
        $total_point = UserPointHistory::select(\DB::raw('SUM(point) as `point`,`icheck_id`'))
            ->where('icheck_id',$icheck_id)->first();
       if($point > floatval($total_point->point)){
           return redirect()->back()->with('error','Bạn đã nhập số điểm lớn hơn số điểm hiện có');
       }

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('PUT',env('DOMAIN_API'). 'users/'.$icheck_id.'/point', [
                'auth' => [env('USER_API'),env('PASS_API')],
                'form_params' => [
                    'source' => 'icheck',
                    'action' => 'exchange',
                    'point' => $point
                ],
            ]);
            $res = json_decode((string) $res->getBody());
        }catch (\Exception $e) {
            return redirect()->back()->with('error','Có lỗi Server! Vui lòng quay lại sau .');

        }

        if($res->status==200){
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'đã thực hiện exchange tài khoản :' . $res->data->icheck_id . 'với số điểm '.$point,
            ]);
            return redirect()->back()->with('success','Cập nhật thành công tài khoản '.$res->data->icheck_id.' với số điểm : '. $res->data->point );
        }else{
            return redirect()->back()->with('error','Có lỗi Server! Vui lòng quay lại sau .');
        }

    }

    public function bonusPoint(Request $request){
        if (auth()->guard('staff')->user()->cannot('user-point-bonus-point')) {
            abort(403);
        }
        $this->validate($request,[
           'icheck_id' => 'required',
            'bonus_point' => 'required',
            'message' => 'required'
        ]);
        $icheck_id = $request->input('icheck_id');
        $point = $request->input('bonus_point');
        $message = $request->input('message');
        $point = intval($point);

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('PUT',env('DOMAIN_API'). 'users/'.$icheck_id.'/point', [
                'auth' => [env('USER_API'),env('PASS_API')],
                'form_params' => [
                    'source' => 'icheck',
                    'action' => 'exchange',
                    'point' => $point
                ],
            ]);
            $res = json_decode((string) $res->getBody());
            if($res->status==200){
                $responseMessage = $client->request('POST',env('DOMAIN_API'). 'notifications/push', [
                    'auth' => [env('USER_API'),env('PASS_API')],
                    'form_params' => [
                        'object_type' => 'message',
                        'to' => [$icheck_id],
                        'message' => $message
                    ],
                ]);
                \App\Models\Enterprise\MLog::create([
                    'email' => auth()->guard('staff')->user()->email,
                    'action' => 'đã thực hiện tặng tài khoản :' . $res->data->icheck_id . 'với số điểm '.$point .'<br> Lý do : '.$message,
                ]);
                $responseMessage = json_decode((string) $responseMessage->getBody());
                if($responseMessage->status == 200){
                    return redirect()->back()->with('success','Tặng điểm thành công tài khoản '.$icheck_id.' với số điểm : '.$point );
                }else{
                    return redirect()->back()->with('error','Tăng điểm thành công nhưng lỗi khi gửi notification');
                }

            }else{
                return redirect()->back()->with('error','Lỗi khi tăng điểm!!');
            }
        }catch (\Exception $e) {
            return redirect()->back()->with('error','Có lỗi Server! Vui lòng quay lại sau .');

        }


    }
}
