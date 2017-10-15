<?php

namespace App\Http\Controllers\AccountActive;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use App\GALib\AnalyticsLib;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\Enterprise\Job;
use App\Models\Enterprise\FailedJobs;
use Illuminate\Support\Facades\Artisan;
use App\Support\JWT;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Icheck\User\Account;
class AccountController extends Controller
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    public function register(Request $request)
    {
        $icheck_id = null;
        if($request->header('icheck_id')){
            $icheck_id = $request->header('icheck_id');
        }

        return view('account_active.register',compact('icheck_id'));
    }

    public function postRegister(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'phone' => 'required|regex:/^\+?\d[0-9-]{9,12}/',

        ]);

        if (empty($request->input('icheck_id'))) {
            return redirect()->route('accountActive::register')->with('error', 'Không tồn tại icheck_id');
        }
        $data = $request->all();
        if (isset($data['_token'])) {
            unset($data['_token']);
        }
        $email = $data['email'];
        $token = JWT::encode($data, 'xac_thuc_user');
        $link = route('accountActive::actived', ['token' => $token]);
//        $transport = $this->mailer->getSwiftMailer()->getTransport();
//        $transport->setHost('mail.icheck.vn');
//        $transport->setPort(25);
//        $transport->setUsername('xacthuc@icheck.vn');
//        $transport->setPassword('icheck@future');
//        $transport->setEncryption('ssl');

        $this->mailer->send(
            'emails.account_active.actived',
            ['token' => $token, 'email' => $email, 'link' => $link],
            function ($message) use ($token, $email) {
                $message->from('xacthuc@icheck.vn', 'Xác thực icheck');
                $message->to($email);
                $message->subject('iCheck - Thông báo xác thực tài khoản');
            }
        );
        return redirect()->back()->with('success', 'Xin vui lòng check mail để hoàn tất xác thực!');
    }

    public function actived(Request $request, $token)
    {
        try {
            $token = JWT::decode($token, 'xac_thuc_user', true);
            if (!isset($token->email) or !isset($token->phone)) {
                throw new \Exception();
            } else {
                $email = $token->email;
                $phone = $token->phone;
                $icheck_id = $token->icheck_id;
                if(!isset($email) or!isset($phone) or !isset($icheck_id)){
                    return redirect()->route('accountActive::register')->with('error', 'Thông tin sai.Vui lòng thử lại');
                }
                $account = Account::where('icheck_id',$icheck_id)->first();
                if($account){
                    $account->email = $email;
                    $account->phone = $phone;
                    $account->email_verified = 1;
                    $account->phone_verified = 1;
                    $account->is_verify = 1;
                    $account->save();
                }
                $message = 'Chúc mừng bạn đã xác thực thành công';
                return view('account_active.actived',compact('message'));
            }
        } catch (\Exception $ex) {
            return redirect()->route('accountActive::register')->with('error', 'Xác thực không thành công! Vui lòng liên hệ với quản trị');
        }

    }
    public function confirm(Request $request,$icheck_id,$token){
        $client = new \GuzzleHttp\Client();
        $status = 0;

        try {
            $res = $client->request('GET',env('DOMAIN_API').'confirm/'.$icheck_id.'/'.$token, [
                'auth' => [env('USER_API'),env('PASS_API')],
            ]);
            $res = json_decode((string) $res->getBody());
            if($res->status==200){
                $status = 1;
                $message = 'Chúc mừng bạn đăng kí thành công';
            }else {
                $message = 'Đăng ký không thành công, xin vui lòng liên hệ với Icheck để được hỗ trợ thông tin';
            }
        }catch (\Exception $e) {
            $message = 'Đăng ký không thành công, xin vui lòng liên hệ với Icheck để được hỗ trợ thông tin';
        }
        return view('account_active.confirm',compact('message','status'));

    }

    public function resetPassword(Request $request,$icheck_id,$token){
        return view('account_active.reset_password',compact('icheck_id','token'));
    }
    public function postResetPassword(Request $request){
        $this->validate($request,[
           'icheck_id' => 'required',
            'token' =>'required',
            'password' => 'required'
        ],[
            'icheck_id.required' => 'Không có icheck id.Thử lại sau',
            'token.required' =>'Không có token.Thử lại sau',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ]);

        $data = $request->all();
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('POST',env('DOMAIN_API').'reset-password/', [
//            $res = $client->request('POST','sandbox.icheck.com.vn/reset-password/', [
                'form_params' => [
                    'code' => $data['token'],
                    'icheck_id' => $data['icheck_id'],
                    'password' => $data['password']
                ],
            ]);

            $res = json_decode((string) $res->getBody());

            if($res->status==200){
                $status = 1;
                $message = 'Reset mật khẩu thành công';
                return view('account_active.confirm',compact('message','status'));
            }else {
                return redirect()->back()->with('error','Lỗi! Xin vui lòng thử lại');
            }
        }catch (\Exception $e) {
            return redirect()->back()->with('error','Lỗi! Xin vui lòng thử lại');

        }
    }


    public function resetPasswordTest(Request $request,$icheck_id,$token){
        return view('account_active.reset_password_test',compact('icheck_id','token'));
    }
    public function postResetPasswordTest(Request $request){
        $this->validate($request,[
            'icheck_id' => 'required',
            'token' =>'required',
            'password' => 'required'
        ],[
            'icheck_id.required' => 'Không có icheck id.Thử lại sau',
            'token.required' =>'Không có token.Thử lại sau',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ]);

        $data = $request->all();
        $client = new \GuzzleHttp\Client();
        try {
//            $res = $client->request('POST',env('DOMAIN_API').'reset-password/', [
            $res = $client->request('POST','sandbox.icheck.com.vn/reset-password/', [
                'form_params' => [
                    'code' => $data['token'],
                    'icheck_id' => $data['icheck_id'],
                    'password' => $data['password']
                ],
            ]);

            $res = json_decode((string) $res->getBody());

            if($res->status==200){
                $status = 1;
                $message = 'Reset mật khẩu thành công';
                return view('account_active.confirm',compact('message','status'));
            }else {
                return redirect()->back()->with('error','Lỗi! Xin vui lòng thử lại');
            }
        }catch (\Exception $e) {
            return redirect()->back()->with('error','Lỗi! Xin vui lòng thử lại');

        }
    }
}
