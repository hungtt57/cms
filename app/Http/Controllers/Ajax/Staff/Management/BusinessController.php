<?php

namespace App\Http\Controllers\Ajax\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests\Staff\Management\Business\StoreBusinessRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\GLN;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use Mail;
use App\Remote\Remote;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Transformers\BusinessTransformer;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $businesses = new Business();

        if ($request->input('q')) {
            $businesses = $businesses->where('name', 'like', '%' . $request->input('q') . '%')
            ->orWhere('email', 'like', '%' . $request->input('q') . '%')->orWhere('login_email', 'like', '%' . $request->input('q') . '%');
        }

        if($request->input('role')){
            $id =  $request->input('role');
            if($id == 1000) {
                $businesses = $businesses->has('roles', '<=', 0);
            }else {
                $businesses = $businesses->whereHas('roles', function ($query) use ($id) {
                    $query->where('brole_id', $id);
                });
            }

        }
        if ($request->has('status') and $request->input('status') !== '') {
            $businesses = $businesses->where('status', $request->input('status'));
        }

        if ($request->has('sort_by')) {
            $businesses = $businesses->orderBy($request->input('sort_by'), $request->input('order', 'asc'));
        }

        $businesses = $businesses->paginate((int) $request->input('per_page', 10));
        $fractal = new Manager();
        $resource = new Collection($businesses->getCollection(), new BusinessTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($businesses));
        $businesses = $fractal->createData($resource)->toJson();

        return $fractal->createData($resource)->toArray();
    }
    public function changeRole(Request $request){
        $id = intval($request->input('id'));
        $idRole = intval($request->input('idRole'));

        $business = Business::find($id);
        if($business){
            if($idRole == 0){
                $business->roles()->sync([]);
            }else{
                $business->roles()->sync([$idRole]);
            }
        }
        return 'oke';

    }
}
