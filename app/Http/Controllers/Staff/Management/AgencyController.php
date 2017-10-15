<?php


namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Social\Agency;
use Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class AgencyController extends Controller
{
    public function index()
    {
        if (auth()->guard('staff')->user()->cannot('list-agency')) {
            abort(403);
        }

        $agencies = Agency::orderBy('createdAt', 'desc')->paginate(8);
        return view('staff.management.agency.index',compact('agencies'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-agency')) {
            abort(403);
        }

        return view('staff.management.agency.form');
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-agency')) {
            abort(403);
        }

        $this->validate($request, [
            'name' =>'required',
        ]);

        $data = $request->all();
        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request(
                'POST',
                'http://upload.icheck.vn/v1/images?uploadType=simple',
                [
                    'body' => file_get_contents($request->file('logo')),
                ]
            );
            $res = json_decode((string)$res->getBody());
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }
        $data['logo'] = $res->prefix;
        $agency = Agency::create($data);
        $agency->save();

        return redirect()->route('Staff::Management::agency@index')
            ->with('success', 'Đã thêm tin tức');

    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-agency')) {
            abort(403);
        }

        $agency = Agency::findOrFail($id);
        return view('staff.management.agency.form',compact('agency'));
    }

    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-agency')) {
            abort(403);
        }

        $this->validate($request, [
            'name' =>'required',
        ]);
        $agency = Agency::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('logo')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('logo')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['logo'] = $res->prefix;
        }
        $agency->update($data);
        $agency->save();
        return redirect()->route('Staff::Management::agency@index')
            ->with('success', 'Đã cập nhật tin tức');
    }


    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-agency')) {
            abort(403);
        }

        $agency = Agency::findOrFail($id);
        $agency->delete();
        return redirect()->route('Staff::Management::agency@index')->with('success', 'Đã xoá thành công');;
    }
}
