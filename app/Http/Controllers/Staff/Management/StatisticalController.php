<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Icheck\User\Account;
use App\Models\Mongo\Product\PComment;
use App\Services\IUpload\IUploadImage;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Session;

class StatisticalController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client =  new Client([
            'base_uri' => env('DOMAIN_API'),
            'auth' => [env('USER_API'), env('PASS_API')],
            'timeout'  => 3.0,
        ]);
    }
    # http://cms.icheck.com.vn/staff@kimochi/management/statistical/list-comment,
    public function listComment(Request $request)
    {


         if (auth()->guard('staff')->user()->cannot('statistical-list-comment')) {
             abort(403);
         }
        $comments = PComment::where('parent','');
        if($request->has('type')){
            $type = intval($request->input('type'));
            $comments = $comments->where('vote_type',$type);
        }else{
            $comments = $comments->whereIn('vote_type',[-1,0,1]);
        }
        if($request->input('date')){
            $date = $request->input('date');
            $startDate = Carbon::createFromTimestamp(strtotime($date));
            $date1 = new DateTime();
            $date1->setTimestamp($startDate->startOfDay()->getTimestamp());
            $date2=new DateTime();
            $date2->setTimestamp($startDate->endOfDay()->getTimestamp());
            $comments = $comments->where('createdAt','>=',$date1)->where('createdAt','<=',$date2);
        }
        if($request->input('resolved')){
            $resolved = $request->input('resolved');
            if($resolved == 1){
                $comments = $comments->where('addPoint',PComment::ADDED_POINT);
            }elseif($resolved == 2){
                $comments = $comments->where('addPoint',PComment::NOT_ADDED_POINT);
            }elseif($resolved == 3){
                $comments = $comments->where('addPoint',null);
            }
        }
        if($request->input('user')){
            $user_name = $request->input('user');
            $query = 'type=user&query='.$user_name;
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('GET',env('DOMAIN_API'). 'search/?'.$query, [
                    'auth' => [env('USER_API'),env('PASS_API')],
                ]);
                $res = json_decode((string) $res->getBody());

            }catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            if($res->status==200){
                $users = $res->data->items;

            }else {
                $users = Account::where('name', 'like', '%' . $request->input('search') . '%')->get();
            }
            $icheck_id = [];
            foreach ($users as $user){
                $icheck_id[] = $user->icheck_id;
            }
            $comments = $comments->whereIn('owner.icheck_id',$icheck_id);
        }
        $comments =$comments->orderBy('createdAt','desc')->paginate(9);

        return view('staff.management.statistical.listComment', compact('comments'));
    }
    // add point
    public function addPoint(Request $request,$id,$icheck_id,$point){
        if(in_array($point,config('listPoint'))){
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
                    $comment = PComment::where('_id',$id)->first();
                    $comment->addPoint = PComment::ADDED_POINT;
                    $comment->save();
                    \App\Models\Enterprise\MLog::create([
                        'email' => auth()->guard('staff')->user()->email,
                        'action' => 'đã thực hiện cộng point cho comment  :' . $res->data->icheck_id . 'với số điểm '.$point,
                    ]);
                    return redirect()->back()->with('success','Cập nhật thành công tài khoản '.$res->data->icheck_id.' với số điểm : '. $res->data->point );
                }else{
                    return redirect()->back()->with('error','Có lỗi Server! Vui lòng quay lại sau .');
                }
            }catch (\Exception $e) {
                return redirect()->back()->with('error','Có lỗi Server! Vui lòng quay lại sau .');

            }
        }else{
            return redirect()->back()->with('error','Số Point được set không đúng');
        }

    }
    public function notAddPoint(Request $request,$id){
        $comment = PComment::where('_id',$id)->first();
        if($comment){
            $comment->addPoint = PComment::NOT_ADDED_POINT;
            $comment->save();
        }

        return redirect()->back()->with('success','Cập nhật thành công' );
    }
    public function listCommentByUser(Request $request,$icheck_id){
        if (auth()->guard('staff')->user()->cannot('statistical-list-comment-by-user')) {
            abort(403);
        }
        $comments =  PComment::where('owner.icheck_id',$icheck_id)->paginate(9);
        return view('staff.management.statistical.listCommentByUser', compact('comments'));
    }
}
