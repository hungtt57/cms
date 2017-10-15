<?php

namespace App\Http\Controllers\Staff\Analytics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use App\Models\Mongo\Product\PComment;
use App\Models\Icheck\Social\Post;
use App\Models\Icheck\Product\Contribute;
use DateTime;
use MongoDB\Driver\Manager;
class ProductCommentController extends Controller
{
    public function index(Request $request)
    {
//        if (auth()->guard('staff')->user()->cannot('analytics-comment')) {
//            abort(403);
//        }

        if (!$request->has('date_range')) {
            $endDate = Carbon::now()->endOfDay();
            $startDate = Carbon::now()->subMonth()->startOfDay();
        } else {
            $dateRange = explode('_', $request->input('date_range'));
            $startDate = Carbon::createFromTimestamp($dateRange[0]);
            $endDate = Carbon::createFromTimestamp($dateRange[1]);
        }


        $chartData = [];
        $tableData = [];


        for ($current = $startDate->copy(); $current->lte($endDate); $current->addDay()) {

            $count_post = Post::whereDate('createdAt', '=', $current->toDateString())->where('type','=','2')->count();
            $chartData['share'][$current->getTimestamp() * 1000] = $count_post;


            $count_contribute = Contribute::whereDate('createdAt', '=', $current->toDateString())->count();
            $chartData['contribute'][$current->getTimestamp() * 1000] = $count_contribute;

            $date = $current->copy();
            $date1 = new DateTime();
            $date1->setTimestamp($date->getTimestamp());
            $date2=new DateTime();
            $date2->setTimestamp($date->endOfDay()->getTimestamp());
            $count_comment = PComment::where('createdAt','>=', $date1)->where('createdAt','<=', $date2)->where('vote_type','!=',null)->count();
            $chartData['comment'][$current->getTimestamp()*1000] = $count_comment;




            $tableData[$current->toDateString()]['comment'] = $count_comment;
            $tableData[$current->toDateString()]['share'] = $count_post;
            $tableData[$current->toDateString()]['contribute'] = $count_contribute;

        }


        $newChartDataPost = [];
        foreach ($chartData['share'] as $key => $value) {
            $newChartDataPost[] = [$key, $value];
        }
        $chartData['share'] = $newChartDataPost;
        $newChartDataComment = [];
        foreach ($chartData['comment'] as $key => $value) {
            $newChartDataComment[] = [$key, $value];
        }
        $chartData['comment'] = $newChartDataComment;

        $newChartData = [];
        foreach ($chartData['contribute'] as $key => $value) {
            $newChartData[] = [$key, $value];
        }
        $chartData['contribute'] = $newChartData;

        return view('staff.analytics.tool.product_comment', compact('startDate', 'endDate', 'gln', 'products', 'chartData', 'tableData'));
    }
}
