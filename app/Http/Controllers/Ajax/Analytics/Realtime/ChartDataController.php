<?php

namespace App\Http\Controllers\Ajax\Analytics\Realtime;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Enterprise\Product;
use App\Models\Social\MRealtime;
use Auth;

class ChartDataController extends Controller
{
    public function getCurrentTimestamp()
    {
        return response()->json(['time' => Carbon::now()->getTimestamp()]);
    }

    public function chartData(Request $request)
    {
            return [];
        $gln = Auth::user()->gln->lists('id')->toArray();
        $products = Product::whereIn('gln_id', $gln)->get();

        if ($request->input('type') == 'perSecond') {
            return [];
            if ($request->has('util')) {
                $util = Carbon::createFromTimeStamp($request->input('util'));
                $since = $util->copy()->subSeconds('60');
                $actions = MRealtime::where('createdAt', '<', $util)
                    ->where('createdAt', '>=', $since)
                    ->whereIn('gtinCode', $products->lists('barcode')->toArray())
                    ->orderBy('createdAt', 'desc')
                    ->get()
                ;

                $data = ['scan' => [], 'like' => [], 'unlike' => [], 'comment' => [], 'vote' => []];
                $data2 = $data;

                while ($util->diffInSeconds($since) > 0) {
                    foreach ($data as $key => $value) {
                        $data[$key][$since->getTimestamp() * 1000] = 0;
                    }

                    $since->addSecond();
                }

                foreach ($actions as $action) {
                    $minute = (new Carbon($action->createdAt->format('Y-m-d H:i:s')))->getTimestamp() * 1000;
                    if ($action->actionType == 'like'
                        and !($action->getAttribute('actionValue.like') == "true"
                            or $action->getAttribute('actionValue.like') == true
                            or $action->getAttribute('actionValue.liked') == 1
                        )
                    ) {
                        $action->actionType = 'unlike';
                    }
                    $data[$action->actionType][$minute] += 1;
                }

                foreach ($data as $key => $value) {
                    foreach ($value as $k => $d) {
                        $data2[$key][] = [$k, $d];
                    }
                }

                return ['data' => $data2];
            } elseif ($request->has('since')) {
                $since = Carbon::createFromTimeStamp($request->input('since'));
                $util = $since->copy()->addSecond();
                $actions = MRealtime::where('createdAt', '<', $util)
                    ->where('createdAt', '>=', $since)
                    ->whereIn('gtinCode', $products->lists('barcode')->toArray())
                    ->orderBy('createdAt', 'desc')
                    ->get()
                ;

                $data = ['scan' => [], 'like' => [], 'unlike' => [], 'comment' => [], 'vote' => []];
                $data2 = $data;
                $second = $since->getTimestamp() * 1000;

                foreach ($data as $key => $value) {
                    $data[$key][$second] = 0;
                }

                foreach ($actions as $action) {
                    if ($action->actionType == 'like' and ($action->getAttribute('actionValue.like') == "false" or $action->getAttribute('actionValue.like') == false)) {
                        $action->actionType = 'unlike';
                    }

                    $data[$action->actionType][$second] += 1;
                }

                foreach ($data as $key => $value) {
                    foreach ($value as $k => $d) {
                        $data2[$key] = [$k, $d];
                    }
                }

                return ['data' => $data2];
            }
        }
        if ($request->has('util')) {
            $util = Carbon::createFromTimeStamp($request->input('util'));
            $since = $util->copy()->subMinutes('30');
            $actions = MRealtime::where('createdAt', '<', $util)
                ->where('createdAt', '>=', $since)
                ->whereIn('gtinCode', $products->lists('barcode')->toArray())
                ->orderBy('createdAt', 'desc')
                ->get()
            ;

            $data = ['scan' => [], 'like' => [], 'unlike' => [], 'comment' => [], 'vote' => []];
            $data2 = $data;

            while ($util->diffInMinutes($since) > 0) {
                foreach ($data as $key => $value) {
                    $data[$key][$since->getTimestamp() * 1000] = 0;
                }

                $since->addMinute();
            }

            foreach ($actions as $action) {
                $minute = (new Carbon($action->createdAt->format('Y-m-d H:i:00')))->getTimestamp() * 1000;
                if ($action->actionType == 'like' and ($action->getAttribute('actionValue.like') == "false" or $action->getAttribute('actionValue.like') == false)) {
                    $action->actionType = 'unlike';
                }
                $data[$action->actionType][$minute] += 1;
            }

            foreach ($data as $key => $value) {
                foreach ($value as $k => $d) {
                    $data2[$key][] = [$k, $d];
                }
            }

            return ['data' => $data2];
        } elseif ($request->has('since')) {
            $since = Carbon::createFromTimeStamp($request->input('since'));
            $util = $since->copy()->addMinute();
            $actions = MRealtime::where('createdAt', '<', $util)
                ->where('createdAt', '>=', $since)
                ->whereIn('gtinCode', $products->lists('barcode')->toArray())
                ->orderBy('createdAt', 'desc')
                ->get()
            ;

            $data = ['scan' => [], 'like' => [], 'unlike' => [], 'comment' => [], 'vote' => []];
            $data2 = $data;
            $minute = $since->getTimestamp() * 1000;

            foreach ($data as $key => $value) {
                $data[$key][$minute] = 0;
            }

            foreach ($actions as $action) {
                if ($action->actionType == 'like' and ($action->getAttribute('actionValue.like') == "false" or $action->getAttribute('actionValue.like') == false)) {
                    $action->actionType = 'unlike';
                }

                $data[$action->actionType][$minute] += 1;
            }

            foreach ($data as $key => $value) {
                foreach ($value as $k => $d) {
                    $data2[$key] = [$k, $d];
                }
            }

            return ['data' => $data2];
        }
    }
}
