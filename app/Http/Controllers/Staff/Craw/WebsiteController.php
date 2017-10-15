<?php

namespace App\Http\Controllers\Staff\Craw;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Craw\Product;
use App\Models\Craw\Website;
use MongoDB\Driver\Manager;
use Response;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

class WebsiteController extends Controller
{
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('DOMAIN_API'),
            'auth' => [env('USER_API'), env('PASS_API')],
            'timeout' => 3.0,
        ]);
        $token = Cache::get('token_login_craw');
        $client = new \GuzzleHttp\Client();
        if (empty($token)) {
            try {
                $res = $client->request('POST', '10.5.11.56:3000/login', [
                    'json' => [
                        'username' => 'icheck',
                        'password' => '!678rgnmkl(34568fcdgv*345bmmlji',
                    ],
                ]);
                $res = json_decode((string)$res->getBody());
                if ($res->code == 200) {
                    $token = $res->token;
                    Cache::put('token_login_craw', $token, 30);
                } else {

                }
            } catch (\Exception $e) {

            }
        }
        $this->token = $token;


    }

    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('craw-websites')) {
            abort(403);
        }
        $websites = Website::select();
        if ($request->input('name')) {
            $name = $request->input('name');
            $websites = $websites->where('name', 'like', '%' . $name . '%');
        }
        if ($request->input('status')) {
            $status = $request->input('status');
            if ($status == 1) {
                $websites = $websites->where('isActive', true);
            }
            if ($status == 2) {
                $websites = $websites->where('isActive', false);
            }
        }
        if($request->input('statusWeb')){
            $statusWeb = $request->input('statusWeb');
            if($statusWeb == 1){
                $list = $this->getListStatusWebsite('Not Started');
                $websites = $websites->whereIn('_id',$list);
            }
            if($statusWeb == 2){
                $list = $this->getListStatusWebsite('Running');
                $websites = $websites->whereIn('_id',$list);
            }
            if($statusWeb == 3){
                $list = $this->getListStatusWebsite('Finished');
                $websites = $websites->whereIn('_id',$list);
//                $websites = $websites->where('status','Finished');
            }
        }
        $websites = $websites->paginate(10);
        foreach ($websites as $website) {
            $status = $this->statusWebsite($website->id);
            $website->statusCraw = $status;
//            $website->status = $status->status;
//            $website->save();
        }

        return view('staff.craw.website.index', compact('websites'));
    }

    public function add(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('craw-websites-add')) {
            abort(403);
        }
        return view('staff.craw.website.form');
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('craw-websites-add')) {
            abort(403);
        }
        $this->validate($request, [
            'xpathUrl' => 'required',
            'url' => 'unique:craw_mongodb.websites,url',
        ], [
            'xpathUrl.required' => 'Nhập đường link sản phẩm để check xpath',
            'url.unique' => 'Đã tồn tại url'
        ]);
        $data = $request->all();
//        try {
//            $context = stream_context_create(
//                array(
//                    'http' => array(
//                        'follow_location' => false
//                    )
//                )
//            );
//            $html = file_get_contents($data['xpathUrl'], false, $context);
//            file_put_contents(storage_path($data['name'] . ".html"), $html);
//        } catch (\Exception $ex) {
//            return redirect()->back()->with('error', 'Lỗi : ' . $ex->getMessage());
//        }
//        $fileUrl = storage_path($data['name'] . ".html");
//        foreach ($data as $key => $value) {
//            if($key == 'xpathUrl'){
//                continue;
//            }
//            if (strpos($key, 'xpath') !== false && $value) {
//                $check = $this->checkXpath($fileUrl, $data['xpathName']);
//                if (!$check) {
//                    return redirect()->back()->with('error', 'Lỗi xpath : ' . $key);
//                }
//            }
//        }
//        unlink($fileUrl);

        if ($data['isActive'] == 1) {
            $data['isActive'] = true;
        } else {
            $data['isActive'] = false;
        }
        if (isset($data['delayTime'])) {
            $data['delayTime'] = intval($data['delayTime']);
        }
        $website = Website::create($data);
        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Thêm websites craw : ' . $website->name,
        ]);
        return redirect()->route('Staff::Craw::website@index')->with('success', 'Thêm thành công');
    }

    public function edit(Request $request, $id)
    {
        if (auth()->guard('staff')->user()->cannot('craw-websites-edit')) {
            abort(403);
        }
        $website = Website::findOrFail($id);
        return view('staff.craw.website.form', compact('website'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('staff')->user()->cannot('craw-websites-edit')) {
            abort(403);
        }
        $website = Website::findOrFail($id);
        $data = $request->all();
        if ($data['isActive'] == 1) {
            $data['isActive'] = true;
        } else {
            $data['isActive'] = false;
        }
        if (isset($data['delayTime'])) {
            $data['delayTime'] = intval($data['delayTime']);
        }
        $website->update($data);
        \App\Models\Enterprise\MLog::create([
            'email' => auth()->guard('staff')->user()->email,
            'action' => 'Sửa websites craw : ' . $website->name,
        ]);
        return redirect()->route('Staff::Craw::website@index')->with('success', 'Cập nhật thành công');
    }

    public function craw(Request $request, $id)
    {
        $token = Cache::get('token_login_craw');
        $client = new \GuzzleHttp\Client();
        if (empty($token)) {
            try {
                $res = $client->request('POST', '10.5.11.56:3000/login', [
                    'json' => [
                        'username' => 'icheck',
                        'password' => '!678rgnmkl(34568fcdgv*345bmmlji',
                    ],
                ]);
                $res = json_decode((string)$res->getBody());
                if ($res->code == 200) {
                    $token = $res->token;
                    Cache::put('token_login_craw', $token, 30);
                } else {
                    return redirect()->back()->with('error', "có lỗi bên server crawler khi login");
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', "có lỗi bên server crawler khi login");
            }
        }

        try {
            $res = $client->request('get', '10.5.11.56:3000/website/crawl?id=' . $id, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $token,
                    'username' => 'icheck'
                ]
            ]);
            $res = json_decode((string)$res->getBody());
            if ($res->code == 200) {
                $website = Website::find($id);
                \App\Models\Enterprise\MLog::create([
                    'email' => auth()->guard('staff')->user()->email,
                    'action' => 'Đăng kí craw websites  : ' .$website->name ,
                ]);
                return redirect()->back()->with('success', "Craw thành công!!");
            } else {
                if ($res->code == 400) {
                    return redirect()->back()->with('error', $res->message);
                }
                return redirect()->back()->with('error', "Lỗi khi đăng kí craw");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "có lỗi bên server crawler ");
        }
    }

    public function checkXpathPost(Request $request)
    {
        $xPath = $request->input('xPath');
        $xPathUrl = $request->input('xPathUrl');


        try {
            $context = stream_context_create(
                array(
                    'http' => array(
                        'follow_location' => false
                    )
                )
            );
            $html = file_get_contents($xPathUrl, false, $context);
            file_put_contents(storage_path("xpath.html"), $html);
            libxml_use_internal_errors(true);
            $doc = new \DOMDocument();

            $doc->loadHTMLFile(storage_path("xpath.html"));
//            $doc->loadHTMLFile($xPathUrl);
            $xpath = new \DOMXpath($doc);

            $result = $xpath->query($xPath);
            $data = [];
            $header = array (
                'Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'
            );
            if (isset($result) and $result->length > 0) {
                foreach ($result as $r) {
                    if (count($data) > 5) {
                        break;
                    }
                    if (isset($r->value) and is_string($r->value)) {
                        $str = $r->value;
                        $str = mb_convert_encoding($str, "UTF-8");
                        if (mb_detect_encoding($str , 'UTF-8', true) === false){
                            $str = utf8_decode($str);
                        }

                        $data[] = convertTextToLink($str);
                    } elseif (isset($r->nodeValue) and is_string($r->nodeValue)) {
                        $str = $r->nodeValue;
                        $str = mb_convert_encoding($str, "UTF-8");
                        if (mb_detect_encoding($str , 'UTF-8', true) === true){
                            $str = utf8_decode($str);
                        }

                        $data[] = convertTextToLink($str);
                    }

                }
                return Response::json(['data' => $data], 200,$header,JSON_UNESCAPED_UNICODE);
            } else {
                $data[] = 'Không tìm thấy giá trị tương ứng với xpath..Check lại!';
                return Response::json(['data' => $data], 200,$header,JSON_UNESCAPED_UNICODE );
            }
        } catch (\Exception $ex) {
            return Response::json(['message' => $ex->getMessage()], 404);
        }

    }

    public function delete(Request $request, $id)
    {
        $website = Website::findOrFail($id);

        if ($website) {
            Product::where('siteId', $id)->delete();
            $name = $website->name;
            $website->delete();
            \App\Models\Enterprise\MLog::create([
                'email' => auth()->guard('staff')->user()->email,
                'action' => 'Xóa websites craw : '.$name,
            ]);
        }
        return redirect()->route('Staff::Craw::website@index')->with('success', 'Xóa thành công');
    }


    public function productCraw(Request $request, $id)
    {
        $website = Website::find($id);
        $products = Product::where('siteId', $id);
        if ($request->input('name')) {
            $products = $products->where('name', 'like', '%' . $request->input('name') . '%');
        }
        $products = $products->paginate(10);
        return view('staff.craw.website.productCraw', compact('products', 'website'));

    }



    public function websiteInCraw(Request $request){
        $token = $this->token;
        $client = new \GuzzleHttp\Client();
        $sites = null;
        try {
            $res = $client->request('get', '10.5.11.56:3000/website/status?filter=Finished', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $token,
                    'username' => 'icheck'
                ]
            ]);
            $res = json_decode((string)$res->getBody());
            $sites = $res;

        } catch (\Exception $e) {
        }
        return view('staff.craw.website.websiteInCraw',compact('sites'));
    }

    public function getWebsiteInCraw(Request $request){
        $token = $this->token;
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('get', '10.5.11.56:3000/website/status?filter=Running', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $token,
                    'username' => 'icheck'
                ]
            ]);
            $res = json_decode((string)$res->getBody());
            if($res){
                $sites = [];
                $product = [];
                $queue = [];
                $visited = [];

                foreach ($res as $site){
                    $sites[] = $site->siteName;
                    $product[] = $site->data->product;
                    $queue[] = $site->data->queue;
                    $visited[] = $site->data->visited;
                }
                return Response::json(['sites' =>$sites,'product' => $product,'queue'=>$queue,'visited' => $visited ],200);
            }
        } catch (\Exception $e) {
            return Response::json([],404);
        }
    }

    private function checkXpath($url, $xpathString)
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        $xpath = new \DOMXpath($doc);
        $result = $xpath->query($xpathString);
        if (isset($result->length) and $result->length > 0) {
            return true;
        }
        return false;

    }

    private function statusWebsite($id)
    {
        $token = $this->token;
        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('get', '10.5.11.56:3000/website/status?ids=' . $id, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $token,
                    'username' => 'icheck'
                ]
            ]);
            $res = json_decode((string)$res->getBody());
            return $res[0];
        } catch (\Exception $e) {

        }
    }
    private function getListStatusWebsite($status){
        if(Cache::get($status)){
            return Cache::get($status);
        }
        $token = $this->token;
        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('get', '10.5.11.56:3000/website/status?filter='.$status, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $token,
                    'username' => 'icheck'
                ]
            ]);
            $res = json_decode((string)$res->getBody());
            $listID =[];
            foreach ($res as $r){
                $listID[] = $r->siteId;
            }
            Cache::put($status, $listID, 5);
            return $listID;
        } catch (\Exception $e) {
            return [];
        }
    }

}
