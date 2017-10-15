<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\Permission;
use App\Models\Enterprise\Role;
use App\Models\Enterprise\UserPermission;
use App\Models\Enterprise\UserRole;
use App\Services\IUpload\IUploadImage;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Models\Enterprise\User;
use App\Http\Controllers\Controller;
use App\Models\Icheck\User\Account;
use App\Models\Mongo\Product\PProduct;
use Illuminate\Pagination\Paginator;

class FakeUserController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('DOMAIN_API'),
            'auth' => [env('USER_API'), env('PASS_API')],
            'timeout'  => 3.0,
        ]);
    }

    public function index()
    {
//        if (auth()->guard('staff')->user()->cannot('list-fakeuser')) {
//            abort(403);
//        }

        $page = Paginator::resolveCurrentPage('page');
        $limit = 24;
        $skip = $limit * ($page - 1);

        try {
            $response = $this->client->get('users', [
                'query' => [
                    'limit' => $limit,
                    'skip' => $skip,
                    'where' => json_encode(['deleted_at' => null, 'is_virtual' => 1])
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] == 200) {
                $users = $data['data'];
            } else {
                $users = [];
            }
        } catch (\Exception $exception) {
            $users = [];
        }

        return view('staff.management.fakeuser.index',compact('users'));
    }

    public function add()
    {
//        if (auth()->guard('staff')->user()->cannot('add-fakeuser')) {
//            abort(403);
//        }
        return view('staff.management.fakeuser.add');
    }

    public function store(Request $request)
    {
//        if (auth()->guard('staff')->user()->cannot('add-fakeuser')) {
//            abort(403);
//        }

        $this->validate($request,[
            'name' =>'required',
        ]);

        $data = $request->only(['name']);

        if ($request->file('image')) {
            $data['image'] = app(IUploadImage::class)->upload($request->file('image'));
        }

        $data['account_id'] = 'f'.Carbon::now()->getTimestamp().random_int(10, 99);

        $message = 'Lỗi khi thêm, hãy thử lại.';

        try {
            $response = $this->client->post('users', [
                'form_params' => [
                    'account_id' => $data['account_id'],
                    'name' => $data['name'],
                    'password' => '696969',
                    'avatar' => $data['image'],
                    'type' => 1,
                    'is_virtual' => 'true',
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] == 200) {
                $user = $data['data'];
                $message = 'Đã thêm';

                return redirect()->route('Staff::Management::fake@index')->with('message', $message);
            } else {
                return redirect()->back()->with('message', $message);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('message', $message);
        }
    }

    public function edit($id)
    {
//        if (auth()->guard('staff')->user()->cannot('edit-user')) {
//            abort(403);
//        }

        $user = null;

        try {
            $response = $this->client->get("users/{$id}");

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] == 200) {
                $user = $data['data'];
            }
        } catch (\Exception $exception) {
            //
        }

        if ($user == null) {
            return redirect()->route('Staff::Management::fake@index')
                ->with('success', 'Không tìm thấy user.');
        }

        return view('staff.management.fakeuser.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
//        if (auth()->guard('staff')->user()->cannot('edit-user')) {
//            abort(403);
//        }

        $this->validate($request,[
            'name' =>'required',
        ]);

        $data = $request->only(['name']);

        if ($request->hasFile('image')) {
            $data['avatar'] = app(IUploadImage::class)->upload($request->file('image'));
        }

        $message = 'Lỗi khi thêm, hãy thử lại.';

        try {
            $response = $this->client->put("users/{$id}", [
                'form_params' => $data
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] == 200) {
                $user = $data['data'];
                $message = 'Đã cập nhật';
            }
        } catch (\Exception $exception) {
            //
        }

        return redirect()->route('Staff::Management::fake@index')
            ->with('success', $message);

    }

    public function block(Request $request, $id)
    {
        $block = $request->input('block') == 'true' ? 'false' : 'true';

        $message = 'Lỗi khi khóa, hãy thử lại.';

        try {
            $response = $this->client->put("users/{$id}", [
                'form_params' => [
                    'status' => $block
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] == 200) {
                $message = 'Đã khóa';
            }
        } catch (\Exception $exception) {
            //
        }

        return redirect()->route('Staff::Management::fake@index')
            ->with('success', $message);
    }

    public function delete($id)
    {
//        if (auth()->guard('staff')->user()->cannot('delete--fakeuser')) {
//            abort(403);
//        }

        $message = 'Lỗi khi xóa, hãy thử lại.';

        try {
            $response = $this->client->delete("users/{$id}");

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] == 200) {
                $user = $data['data'];
                $message = 'Đã xóa';
            }
        } catch (\Exception $exception) {
            //
        }

        return redirect()->route('Staff::Management::fake@index')->with('message', $message);
    }

    public function collaboratorApp(){
        if (auth()->guard('staff')->user()->cannot('list-collaborator')) {
            abort(403);
        }

        $collaborators = Account::where('is_collaborator', Account::COLLABORATOR)->orderBy('createdAt', 'desc')->get();
        return view('staff.management.fakeuser.collaboratorApp', compact('collaborators'));
    }

    public function listPostLike(Request $request)
    {
        $posts = [];
        $page = Paginator::resolveCurrentPage('page');
        $limit = 24;
        $skip = $limit * ($page - 1);

        try {
            $response = $this->client->get('posts', [
                'query' => [
                    'limit' => $limit,
                    'skip' => $skip,
                    'where' => json_encode([
                        'object_type'=> 4,
                        'deleted_at' => null
                    ])
                ]
            ]);

            $response = json_decode((string) $response->getBody(), true);

            if ($response['status'] == 200) {
                $posts = $response['data'];
            }
        } catch (\Exception $exception) {
            //
        }

        return view('staff.management.fakeuser.list-post-like', compact('posts'));
    }

    public function likePost(Request $request)
    {
        $posts = $request->input('posts', []);
        $user = $request->input('uid');

        if (! is_array($posts)) {
            return redirect()->back();
        }

        $posts = array_slice($posts, 0, 4);

        $promises = [];

        foreach ($posts as $post) {
            $promises[] = $this->client->postAsync("posts/{$post}/likes", [
                'form_params' => [
                    "icheck_ids" => [$user],
                    "is_like" => true
                ]
            ]);
        }

        return redirect()->route('Staff::Management::fake@index');
    }
}
