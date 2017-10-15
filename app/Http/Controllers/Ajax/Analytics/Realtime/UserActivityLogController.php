<?php

namespace App\Http\Controllers\Ajax\Analytics\Realtime;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Enterprise\Product;
use App\Models\Social\MRealtime;
use Auth;

class UserActivityLogController extends Controller
{
    public function index(Request $request)
    {
        return [];
        $gln = Auth::user()->gln->lists('id')->toArray();
        $products = Product::whereIn('gln_id', $gln)->get();

        if ($request->has('util')) {
            $util = Carbon::createFromTimeStamp($request->input('util'));
            $actions = MRealtime::where('createdAt', '<', $util)
                ->whereIn('gtinCode', $products->lists('barcode')->toArray())
                ->orderBy('createdAt', 'desc')
                ->take((int) $request->input('limit', 30))
                ->get()
            ;
        } elseif ($request->has('since')) {
            $since = Carbon::createFromTimeStamp($request->input('since'));
            $actions = MRealtime::where('createdAt', '>=', $since)
                ->whereIn('gtinCode', $products->lists('barcode')->toArray())
                ->orderBy('createdAt', 'desc')
                ->get()
            ;
        }

        $actions->map(function ($action) {
            $action->action = $action->getAttribute('actionType');
            $action->data = [];

            switch ($action->actionType) {
                case 'like':
                    if (!($action->getAttribute('actionValue.like') == "true"
                        or $action->getAttribute('actionValue.like') == true
                        or $action->getAttribute('actionValue.liked') == 1
                    )) {
                        $action->action = 'unlike';
                    }

                    break;

                case 'scan':
                    $action->data = ['scanTimes' => $action->actionValue['time']];
                    break;

                case 'comment':
                    $action->data = [
                        'content' => $action->actionValue['message'],
                        'image' => $action->getAttribute('actionValue.image') ? 'http://ucontent.icheck.vn/' . $action->getAttribute('actionValue.image') . '_original.jpg' : null,
                    ];
                    break;

                case 'vote':
                    $action->data = ['point' => $action->actionValue['rate']];
                    break;
            }

            $action->time = $action->createdAt->toIso8601String();

            unset($action->_id);
            unset($action->facebook_id);
            unset($action->createdAt);
            unset($action->actionType);
            unset($action->actionValue);

            return $action;
        });

        return ['data' => $actions];
    }
}
