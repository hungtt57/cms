<?php


namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Social\MSurvey;
use Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class SurveyController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-survey')) {
            abort(403);
        }

        $surveys = MSurvey::paginate(15);
        ['location'=>'array'];


        return view('staff.management.survey.index',compact('surveys','a'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-survey')) {
            abort(403);
        }

        return view('staff.management.survey.form');
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-survey')) {
            abort(403);
        }

        $this->validate($request, [
            'message' =>'required',
            'link' =>'required',
            'location' =>'required',
            'status' =>'required',
            'put_first' =>'required',
        ]);

        $data = $request->all();
        $data['status'] = (bool) $data['status'];
        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request(
                'POST',
                'http://upload.icheck.vn/v1/images?uploadType=simple',
                [
                    'body' => file_get_contents($request->file('image')),
                ]
            );
            $res = json_decode((string)$res->getBody());
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }
        $data['image'] = $res->prefix;
        $data['location']= explode(",", $data['location']);
        $survey = MSurvey::create($data);



        $survey->save();

        return redirect()->route('Staff::Management::survey@index')
            ->with('success', 'Đã thêm tin tức');

    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-survey')) {
            abort(403);
        }

        $survey = MSurvey::findOrFail($id);
        return view('staff.management.survey.form',compact('survey'));
    }

    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-survey')) {
            abort(403);
        }

        $this->validate($request, [
            'message' =>'required',
            'link' =>'required',
            'location' =>'required',
            'status' =>'required',
            'put_first' =>'required',
        ]);
        $survey = MSurvey::findOrFail($id);
        $data = $request->all();
        $data['status'] = (bool) $data['status'];
        if ($request->hasFile('image')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('image')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['image'] = $res->prefix;
        }
        $data['location']= explode(",", $data['location']);
        $survey->update($data);
        $survey->save();
        return redirect()->route('Staff::Management::survey@index',$survey->id)
            ->with('success', 'Đã cập nhật tin tức');
    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-survey')) {
            abort(403);
        }

        $survey = MSurvey::findOrFail($id);
        $survey->delete();
        return redirect()->route('Staff::Management::survey@index')->with('success', 'Đã xoá thành công');;
    }

}
