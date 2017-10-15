<?php

namespace App\Http\Controllers\Staff\Analytics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Carbon\Carbon;


use Spatie\Analytics\Period;
use App\GALib\AnalyticsLib;
class GAController extends Controller
{
    public function index(Request $request)
    {
//        if (auth()->guard('staff')->user()->cannot('analytics-comment')) {
//            abort(403);
//        }

        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subDays(1)->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }

        $chartData = [];
        $tableData = [];

        $analytic = new AnalyticsLib();
        $realtime_user = $analytic->getRealActiveUser();

        $period = Period::create($startDate, $endDate);
        $daily_users  = $analytic->fetchTotalVisitorsAndPageViews($period);

        foreach ($daily_users as $daily_user){
            $chartData['daily_users'][$daily_user['date']->startOfDay()->getTimestamp() * 1000] = $daily_user['visitors'];
        }
        $newUsers = $analytic->getNewUser($period);

        foreach ($newUsers as $newUser){
            $chartData['new_user'][$newUser['time']->getTimestamp() * 1000] = $newUser['total'];
        }

        for ($current = $startDate->copy(); $current->lte($endDate); $current->addDay()) {
            $data =  $analytic->getPeakCCU($current);
            $tableData[$current->toDateString()]['time'] = $data['time'];
            $tableData[$current->toDateString()]['total'] = $data['total'];
        }


        $newChartData = [];
        foreach ($chartData['daily_users'] as $key => $value) {
            $newChartData[] = [$key, $value];
        }
        $chartData['daily_users'] = $newChartData;
        $newChartDataComment = [];
        foreach ($chartData['new_user'] as $key => $value) {
            $newChartDataComment[] = [$key, $value];
        }
        $chartData['new_user'] = $newChartDataComment;


        return view('staff.analytics.tool.ga', compact('realtime_user','startDate','endDate','chartData','tableData'));
    }
}
