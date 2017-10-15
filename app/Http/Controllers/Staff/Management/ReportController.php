<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Mongo\Social\ICheckReport;
use App\Models\Social\Product as SProduct;
use GuzzleHttp\Exception\RequestException;
use App\Models\Icheck\Product\ProductReport;
use App\Models\Icheck\Social\Report;
use App\Models\Icheck\Social\Post;
use Auth;
use Cache;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-report')) {
            abort(403);
        }
        if ($request->input('type') && $request->input('type')=='1')
        {
            $type = 'feed';
            $reports = Report::orderBy('createdAt', 'desc')
                        ->where('status', ICheckReport::STATUS_PENDING)
                        ->paginate(20);
        }
        else 
        {
            $type = 'product';
            $reports = ProductReport::orderBy('createdAt', 'desc')
                        ->where('status', ICheckReport::STATUS_PENDING)
                        ->paginate(20);
        }
        return view('staff.management.report.index', compact('reports','type'));
    }

    public function show($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-report')) {
            abort(403);
        }

        $report = Report::findOrFail($id);

        return view('staff.management.report.show', compact('report'));
    }

    public function deleteFeed($id)
    {
       

        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->delete(env('DOMAIN_API') . 'posts/'. $id, [
                'auth' => [env('USER_API'), env('PASS_API')],
            ]);
            $res = json_decode((string)$res->getBody());

            if ($res->status != 200) return redirect()->route('Staff::Management::report@index')
            ->with('danger', 'Xóa post lỗi');
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }

        //Xoa cac report lien quan den feed da xoa
        $reports = Report::where('object_id',$id)->get();
        foreach ($reports as $report) {
            $report->delete();
        }

        return redirect()->route('Staff::Management::report@index',['type' => '1'])
            ->with('success', 'Đã xóa Post');
    }

    public function resolve(Request $request)
    {
        if ($request->input('type') && $request->input('type')=='1')
        {
            $report = Report::find($request->input('id'));
            $report->status = 1;
            $report->save();
        }
        else 
        {
            $report = ProductReport::find($request->input('id'));
            $report->status = 1;
            $report->save();
        }
        return redirect()->back()
            ->with('success', 'Đã giải quyết báo cáo nhé');
    }

    public function pending($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-report')) {
            abort(403);
        }

        $report = ICheckReport::findOrFail($id);
        $report->resolvedBy = null;
        $report->status = ICheckReport::STATUS_PENDING;
        $report->save();

        return redirect()->back()
            ->with('success', 'Đã chuyển về chờ giải quyết');
    }
}
