<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Models\Social\Product;
use App\Models\Icheck\Product\Product as Product2;
use App\Models\Enterprise\Product;
use App\Models\Enterprise\GLN;
use Auth;
use Carbon\Carbon;


use App\GALib\AnalyticsClientLib;

//use Spatie\Analytics\Analytics;
//use Spatie\Analytics\AnalyticsClient;
use Spatie\Analytics\Period;
use App\GALib\AnalyticsLib;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Input;
use Hash;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Response;
use App\Models\Enterprise\BusinessChart;
class DashboardController extends Controller
{
    public function index(Request $request)
    {


        return view('business.dashboard');

    }
    public function getChartData(Request $request){

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $startDate = Carbon::createFromTimestamp($startDate)->startOfDay()->getTimeStamp();
        $endDate = Carbon::createFromTimestamp($endDate)->startOfDay()->getTimeStamp();
        $business = Auth::user();
        $charts = BusinessChart::where('business_id',$business->id)->where('date','>=',$startDate)->where('date','<=',$endDate)->orderBy('date','asc')->get();
        $result = [];
        foreach ($charts as $chart){
            $result[] = [$chart->date*1000,$chart->total];
        }
        return $result;



    }
    public function getReportProduct($gtin, Request $request)
    {

        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(29)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }
        $analytics = new AnalyticsLib();
        $chartData = $analytics->getGtinCode($startDate, $endDate, $gtin);


        $pro_show = [];
        $pro_scan = [];
        $pro_click = [];
        $pro_comment = [];
        $pro_like = [];
        $pro_vote_good = [];
        $pro_vote_normal = [];
        $pro_vote_bad = [];
        $pro_review = [];

        $pro_share_icheck = [];
        $pro_share_facebook = [];
        $pro_chat = [];
        $date = [];
        foreach ($chartData as $key => $value) {

            if ($value['event'] == 'pro_show') {
                $pro_show[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_show'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_scan') {
                $pro_scan[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_scan'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_click') {
                $pro_click[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_click'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_comment') {
                $pro_comment[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_comment'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_like') {
                $pro_like[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_like'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_vote_good') {
                $pro_vote_good[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_vote_good'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_vote_normal') {
                $pro_vote_normal[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_vote_normal'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_vote_bad') {
                $pro_vote_bad[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_vote_bad'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_review') {
                $pro_review[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
                $date[$value['date']->getTimestamp()]['pro_review'] = $value['totalEvent'];
            } elseif ($value['event'] == 'pro_share_icheck') {
                $date[$value['date']->getTimestamp()]['pro_share_icheck'] = $value['totalEvent'];
                $pro_share_icheck[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
            } elseif ($value['event'] == 'pro_share_facebook') {
                $date[$value['date']->getTimestamp()]['pro_share_facebook'] = $value['totalEvent'];
                $pro_share_facebook[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
            } elseif ($value['event'] == 'pro_chat') {
                $date[$value['date']->getTimestamp()]['pro_chat'] = $value['totalEvent'];
                $pro_chat[] = [$value['date']->getTimestamp() * 1000, $value['totalEvent']];
            }


        }
        ksort($date);
        $dates = collect($date);
        $page = $request->get('page', 1);
        $perPage = 10;
        $dates = new LengthAwarePaginator(
            $dates->forPage($page, $perPage), $dates->count(), $perPage, $page, ['path' => Paginator::resolveCurrentPath()]
        );

        return view('business.gtin_report', compact('dates', 'chartData', 'startDate', 'endDate', 'pro_show', 'pro_click', 'pro_scan', 'pro_like', 'pro_comment', 'pro_vote_good', 'pro_vote_normal', 'pro_vote_bad', 'pro_review', 'pro_share_icheck', 'pro_share_facebook', 'pro_chat'));
    }

    public function password_change_form(){
        return view('business.change_password');
    }
    public function password_change(Request $request){
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        $user = Auth::user();

        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
            'new_password2' => 'required|same:new_password',
        ],[
            'old_password.required' => 'Vui lòng nhập lại mật khẩu cũ',
                'new_password.required' => 'Vui lòng nhập lại mật khẩu mới',
                'new_password2.required' => 'Vui lòng nhập xác nhận mật khẩu mới',
                'new_password2.same' => 'Mật khẩu mới xác nhận chưa đúng',
            ]
        );

        if (!Hash::check($oldPassword, $user->password)) {
            return redirect()
                ->back()
                ->withErrors('Mật khẩu cũ không chính xác')
                ;
        }

        if (Hash::check($newPassword, $user->password)) {
            return redirect()
                ->back()
                ->withErrors('Mật khẩu mới không được giống mật khẩu cũ')
                ;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return redirect()
            ->back()
            ->withSuccess('Thay đổi mật khẩu thành công')
            ;
    }

    public function image(Request $request)
    {
        if ($request->input('via_url') == 1) {

            $fname = time() . mt_rand(0, 9999);
            $c = @file_get_contents($request->input('url'));

            if (!$c) {
                return response('', 400);
            }

            @file_put_contents(storage_path('app/' . $fname), $c);

            if (!getimagesize(storage_path('app/' . $fname))) {
                return response('', 400);
            }

            $size = filesize(storage_path('app/' . $fname));

            if(intval($size) < 20480){
                return Response::json(['error' => 'Dung lượng ảnh tối thiểu 20KB'], 404); // Status code here
            }

            $client = new Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents(storage_path('app/' . $fname)),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            @unlink(storage_path('app/' . $fname));

            return ['prefix' => $res->prefix, 'url' => get_image_url($res->prefix)];
        }
        $this->validate($request, [
            'file' => 'image',
        ]);

        $size = filesize($request->file('file'));

        if(intval($size) < 20480){
             return Response::json(['error' => 'Dung lượng ảnh tối thiểu 20KB'], 404); // Status code here
        }

        $client = new Client();

        try {
            $res = $client->request(
                'POST',
                'http://upload.icheck.vn/v1/images?uploadType=simple',
                [
                    'body' => file_get_contents($request->file('file')),
                ]
            );
            $res = json_decode((string) $res->getBody());
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }

        return ['prefix' => $res->prefix, 'url' => get_image_url($res->prefix)];
    }

    public function downloadForm(Request $request){
        $file_path = public_path().'/Form-DN.xlsx';

        if (file_exists($file_path))
        {
            // Send Download
            return Response::download($file_path, 'Form-DN.xlsx', [
            ]);
        }
        else
        {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function downloadPP(Request $request){
        $file_path = public_path().'/Form-DN PP.xlsx';

        if (file_exists($file_path))
        {
            // Send Download
            return Response::download($file_path, 'Form-DN PP.xlsx', [
            ]);
        }
        else
        {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }
    public function hdsd(Request $request){
        return view('business.hdsd');
    }
    function retrieve_remote_file_size($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        return intval($size);
    }
}
