<?php

namespace App\Http\Controllers\Staff\Analytics;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Carbon\Carbon;
use App\Models\Mongo\Social\PostComment;
use App\Models\Icheck\Social\Post;
use DateTime;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
class PostCommentController extends Controller
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

            $count_post = Post::whereDate('createdAt', '=', $current->toDateString())->count();
            $chartData['post'][$current->getTimestamp() * 1000] = $count_post;

            $date = $current->copy();
            $date1 = new DateTime();
            $date1->setTimestamp($date->getTimestamp());

            $date2=new DateTime();
            $date2->setTimestamp($date->endOfDay()->getTimestamp());
            $count_comment = PostComment::where('createdAt','>=', $date1)->where('createdAt','<=', $date2)->count();

            $chartData['comment'][$current->getTimestamp()*1000] = $count_comment;

            $tableData[$current->toDateString()]['comment'] = $count_comment;
            $tableData[$current->toDateString()]['post'] = $count_post;
        }


        $newChartDataPost = [];
        foreach ($chartData['post'] as $key => $value) {
            $newChartDataPost[] = [$key, $value];
        }
        $chartData['post'] = $newChartDataPost;
        $newChartDataComment = [];
        foreach ($chartData['comment'] as $key => $value) {
            $newChartDataComment[] = [$key, $value];
        }
        $chartData['comment'] = $newChartDataComment;

        return view('staff.analytics.tool.post_comment', compact('startDate', 'endDate', 'gln', 'products', 'chartData', 'tableData'));
    }
}
