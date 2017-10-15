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
class ChatController extends Controller
{
    public function index(Request $request){
        if (auth()->guard('staff')->user()->cannot('chat')) {
            abort(403);
        }
        $icheck_id = 'i-1469083968959';

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('GET',env('DOMAIN_API'). 'users/'.$icheck_id.'/firebase-token', [
                'auth' => [env('USER_API'),env('PASS_API')],
            ]);
            $res = json_decode((string) $res->getBody());
        }catch (RequestException $e) {
            return $e->getResponse();
        }

        if($res->status==200){
            $token = $res->data;
        }
        try {
            $res = $client->request('GET',env('DOMAIN_API'). 'users/'.$icheck_id.'/icheck-token', [
                'auth' => [env('USER_API'),env('PASS_API')],
            ]);
            $res = json_decode((string) $res->getBody());
        }catch (RequestException $e) {
            return $e->getResponse();
        }

        if($res->status==200){
            $tokenIcheck = $res->data;
        }
        return view('staff.management.chat.index',compact('token','tokenIcheck'));
    }

    public function search(Request $request){
        $key = $request->input('term');
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('GET',env('DOMAIN_API'). 'search/?type=user&query='.$key.'&limit=10&skip=0', [
                'auth' => [env('USER_API'),env('PASS_API')],
            ]);
            $res = json_decode((string) $res->getBody());

        }catch (RequestException $e) {
            return $e->getResponse();
        }

        if($res->status==200){
            $user = $res->data;
            return Response::json($user->items);
        }
        return Response::json([],500);

    }
    public function searchGtin(Request $request){

        $key = $request->input('term');
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('GET',env('DOMAIN_API').'search/?type=product&query='.$key.'&limit=10&skip=0', [
                'auth' => [env('USER_API'),env('PASS_API')],
            ]);
            $res = json_decode((string) $res->getBody());

        }catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }
        if($res->status==200){
            $product = $res->data;
            return Response::json($product->items);
        }
        return Response::json([],500);
    }
    public function sendNotification(Request $request){
        //        POST /notifications/push
        //Host: https://core.icheck.com.vn
        //Params {
        //            to: icheckId
        //message: string
        //object_type: string(Inbox)
        //object_id: string(RoomId)
        //}

        $fromUser = $request->input('fromUser');
        $content = $request->input('content');
        $name = $request->input('name');
        $toUser = $request->input('toUser');
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('POST',env('DOMAIN_API').'sns/push', [
                'auth' => [env('USER_API'),env('PASS_API')],
                'form_params' => [
                    'to' => $toUser,
                    'message' => $content,
                    'object_type' => 'inbox',
                    'object_id' => $fromUser,
                    'title' => $name
                ]
            ]);

            $res = json_decode((string) $res->getBody());

        }catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }

        if($res->status==200){
            return 'oke';
        }

    }

}
