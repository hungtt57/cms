<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\ProductCategory;
//use App\Models\Social\Category;
use App\Models\Icheck\Product\Category;
use App\Models\Social\MGroupType;
use App\Models\Social\MSurvey;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AppSettingController extends Controller
{
    public function indexGroupType()
    {
        $types = MGroupType::paginate(16);

        return view('staff.management.settings.grouptype.index', compact('types'));
    }

    public function createGroupType()
    {
        $categories = Category::all()->groupBy('parent_id');
        $categories = ProductController::r($categories, 12);

        return view('staff.management.settings.grouptype.create', compact(['categories']));
    }

    public function storeGroupType(Requests\Staff\Mangement\AppSetting\StoreGroupType $request)
    {
        $data = $request->only(['type', 'name', 'categories_refer']);

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request(
                'POST',
                'http://upload.icheck.vn/v1/images?uploadType=simple',
                [
                    'body' => file_get_contents($request->file('icon')),
                ]
            );
            $res = json_decode((string) $res->getBody());
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }

        $data['icon'] = $res->prefix;

        $data = array_filter($data, function ($d) {
            return ! is_null($d);
        });

        $type = MGroupType::create($data);

        return redirect()->route('Staff::Management::settings@grouptype.index');
    }

    public function editGroupType(MGroupType $type) 
    {
        $categories = Category::all()->groupBy('parent_id');
        $categories = ProductController::r($categories, 12);
        
        return view('staff.management.settings.grouptype.edit', compact('type', 'categories'));
    }

    public function updateGroupType(Requests\Staff\Mangement\AppSetting\UpdateGroupType $request, MGroupType $type)
    {
        $data = $request->only(['name', 'categories_refer']);

        if ($request->hasFile('icon')) {
            $client = new \GuzzleHttp\Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents($request->file('icon')),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['icon'] = $res->prefix;
        }

        $data = array_filter($data, function ($d) {
            return ! is_null($d);
        });

        $type->update($data);

        return redirect('/settings/grouptype');
    }

    public function deleteGroupType(MGroupType $type)
    {
        $type->delete();

        return redirect('/settings/grouptype');
    }

    public function indexSurvey()
    {
        $surveys = MSurvey::where('status', true)->paginate(16);

        return view('staff.management.settings.survey.index', compact('surveys'));
    }

    public function createSurvey()
    {
        return view('staff.management.settings.survey.create');
    }

    public function storeSurvey(Requests\Staff\Mangement\AppSetting\StoreSurvey $request)
    {
        $data = $request->only(['message', 'location', 'link']);
        
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
        $data['location'] = explode(', ', trim($data['location']));
        $data['location'] = array_unique($data['location']);

        $data = array_filter($data, function ($d) {
            return ! is_null($d);
        });

        $data['status'] = true;
        $data['join_count'] = 0;
        $data['joined_users'] = [];

        $survey = MSurvey::create($data);

        return redirect()->route('Staff::Management::setting@survey.index');
    }

    public function editSurvey(MSurvey $survey)
    {
        return view('staff.management.settings.survey.edit', compact('survey'));
    }

    public function updateSurvey(Requests\Staff\Mangement\AppSetting\UpdateSurvey $request, MSurvey $survey)
    {
        $data = $request->only(['message', 'location', 'link']);

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

        $data['location'] = explode(', ', trim($data['location']));
        $data['location'] = array_unique($data['location']);

        $data = array_filter($data, function ($d) {
            return ! is_null($d);
        });

        $survey->update($data);

        return redirect()->route('Staff::Management::setting@survey.index');
    }

    public function deleteSurvey(MSurvey $survey)
    {
        $survey->update(['status' => false]);

        return redirect()->route('Staff::Management::setting@survey.index');
    }
}
