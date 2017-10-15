<?php

namespace App\Http\Controllers\Staff;


use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Models\Icheck\User\Account;
use App\Models\Mongo\Product\PProduct;
use App\Events\ImportFileManagerUser;
use Event;


class ManagerUserController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('managerUser')) {
            abort(403);
        }
        $page = 1;
        $search = '';
        $skip = 0;
        $search_id = '';
        $is_shop = 2;
        if($request->input('page')){

            $page = $request->input('page');
            $skip = ($page-1)*10;
        }
        if($request->input('is_shop')){
            $is_shop = $request->input('is_shop');
        }

        if ($request->input('search')){

                    $key = $request->input('search');
                    $search =  $request->input('search');
                    $client = new \GuzzleHttp\Client();
                    $query = 'type=user&limit=10&&skip='.$skip.'&query='.$key;
                    if($is_shop!=2){
                        $query = $query.'&shop='.$is_shop;
                    }

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

                        $users = Account::where('name', 'like', '%' . $request->input('search') . '%')->limit(10)->skip($skip)->get();
                    }


        }
        elseif($request->input('search_id')){

            $users = Account::where('icheck_id',$request->input('search_id'))->limit(10)->skip($skip)->get();

        }
        else{
            if($is_shop!=2){
                $users =  Account::where('is_shop',$is_shop)->limit(10)->skip($skip)->get();
            }else{
                $users =  Account::limit(10)->skip($skip)->get();
            }

        }
        return view('staff.managerUser.index',compact('users','search','page','search_id','is_shop'));
    }

    public function import($icheck_id,Request $request){


        if($request->file('file')){

            if ($request->file('file')->isValid()) {

            $filename = time() . '_' . mt_rand(1111, 9999) . '_' . $request->file('file')->getClientOriginalName();
            $request->file('file')->move(storage_path('app/import/shop'), $filename);

            Event::fire(new ImportFileManagerUser(auth()->guard('staff')->user()->email, storage_path('app/import/shop/' . $filename), $request->file('file')->getClientOriginalName(), $icheck_id));
            }
        }
        return redirect()->back()
            ->with('success', 'Lên lịch import thành công');

    }
    public function block($id,Request $request){
       $user = Account::find($id);
        if($user->status == 0){
            $user->status = 1;
            $user->save();
            return redirect()->back()
                ->with('success', 'Unblock ' . $user->name . ' thành công');
        }else{
            $user->status =0;
            $user->save();
            return redirect()->back()
                ->with('success', 'Block ' . $user->name . ' thành công');
        }


    }

    public function verify($id,Request $request){
        $user = Account::find($id);
        if($user->is_verify == 0){
            $user->is_verify = 1;
            $user->save();
            return redirect()->back()
                ->with('success', 'Xác thực ' . $user->name . ' thành công');
        }else{
            $user->is_verify =0;
            $user->save();
            return redirect()->back()
                ->with('success', 'Hủy xác thực  ' . $user->name . ' thành công');
        }


    }
}
