<?php

namespace App\Http\Controllers\Staff\Management;

use App\Models\Icheck\User\Account;
use App\Services\IUpload\IUploadImage;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class VirtualUserPostController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client =  new Client([
            'base_uri' => env('DOMAIN_API'),
            'auth' => [env('USER_API'), env('PASS_API')],
            'timeout'  => 3.0,
        ]);
    }

    public function listPost(Request $request, $id)
    {
        try {
            $response = $this->client->get("users/{$id}");

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $user = $data['data'];
        } catch (\Exception $exception) {
            return response('Error: '.$exception->getMessage(), 500);
        }

        $posts = [];
        $page = Paginator::resolveCurrentPage('page');
        $limit = 24;
        $skip = $limit * ($page - 1);

        try {
            $response = $this->client->get('posts', [
                'query' => [
                    'object_type'=> '4,2',
                    'limit' => $limit,
                    'skip' => $skip,
                    'where' => json_encode([
                        'icheck_id' => $user['icheck_id'],
                        'deleted_at' => null
                    ])
                ]
            ]);

            $response = json_decode((string) $response->getBody(), true);

            if ($response['status'] != 200) {
                return response()->json($response);
            }

            $posts = $response['data'];
        } catch (\Exception $exception) {
            return response("Error: ".$exception->getMessage());
        }

        return view('staff.management.fakeuser.post.index', compact('posts', 'id'));
    }

    public function addPost(Request $request, $id)
    {
        return view('staff.management.fakeuser.post.add', compact('id'));
    }

    public function create(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|min:3:max:255',
            'description' => 'required|min:3|max:255',
            'content' => 'required|min:3|max:255',
            'image' => 'required|image',
        ]);

        try {
            $response = $this->client->get("users/{$id}");
            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $user = $data['data'];
        } catch (\Exception $exception) {
            return response('Error: '.$exception->getMessage());
        }

        $news = $request->only(['title', 'description', 'content']);

        if ($request->hasFile('image')) {
            $news['thumb'] = app(IUploadImage::class)->upload($request->file('image'));
        }

        try {
            $response = $this->client->post("posts", [
                "json" => [
                    "owner" => [
                        "icheck_id" => $user['icheck_id']
                    ],
                    "news" => $news
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $message = 'Đã thêm.';
            return redirect()->route('Staff::Management::virtualUser@post.list', ['user' => $id])->with('message', $message);
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }
    }

    public function edit(Request $request, $user, $post)
    {
        try {
            $response = $this->client->get("users/{$user}");
            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $user = $data['data'];
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }

        try {
            $response = $this->client->get("posts/{$post}");
            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $post = $data['data'];
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }

        return view('staff.management.fakeuser.post.edit', compact('user', 'post'));
    }

    public function update(Request $request, $user, $post)
    {
        $this->validate($request, [
            'title' => 'required|min:3:max:255',
            'description' => 'required|min:3|max:255',
            'content' => 'required|min:3|max:255',
            'image' => 'image',
        ]);

        try {
            $response = $this->client->get("users/{$user}");
            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $user = $data['data'];
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }

        try {
            $response = $this->client->get("posts/{$post}");
            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $post = $data['data'];
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }

        $news = $request->only(['title', 'description', 'content']);

        if ($request->hasFile('image')) {
            $news['thumb'] = app(IUploadImage::class)->upload($request->file('image'));
        }

        $message = 'Lỗi khi thêm, hãy thử lại.';

        try {
            $response = $this->client->put("posts/{$post['id']}", [
                "json" => [
                    "news" => $news
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $message = 'Đã thêm.';
            return redirect()->route('Staff::Management::virtualUser@post.list', ['user' => $user['id']])->with('message', $message);
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }
    }

    public function delete(Request $request, $user, $post)
    {
        try {
            $response = $this->client->delete("posts/{$post}");

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $message = 'Đã xóa.';
            return redirect()->route('Staff::Management::virtualUser@post.list', ['user' => $user])->with('message', $message);
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }
    }

    public function comments(Request $request, $post)
    {
        $comments = [];

        try {
            $response = $this->client->get("comments", [
                'query' => [
                    'service' => 'post',
                    'sort' => 'createdAt asc',
                    'where' => json_encode(['object_id' => $post, 'deleted_at' => null])
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $comments = $data['data'];
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }

        $users = [];

        try {
            $response = $this->client->get('users', [
                'query' => [
                    'limit' => 20,
                    'where' => json_encode(['deleted_at' => null, 'is_virtual' => 1])
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $users = $data['data'];
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }

        return view('staff.management.fakeuser.post.comments', compact('post', 'comments', 'users'));
    }

    public function createComment(Request $request, $post)
    {
        $this->validate($request, [
            'user' => 'required',
            'message' => 'required',
            'file' => 'image',
        ]);

        $data = [
            'icheck_id' => $request->input('user'),
            'object_id' => $post,
            'content' => $request->input('message'),
            'service' => 'post'
        ];

        if ($request->hasFile('file')) {
            $data['attachments'] = [[
                'type' => 'image',
                'link' => app(IUploadImage::class)->upload($request->file('file'))
            ]];
        }

        try {
            $this->client->post('comments', [
                'json' => $data
            ]);
        } catch (\Exception $exception) {
            return redirect("Error: ".$exception->getMessage());
        }

        return redirect()->back();
    }

    public function likeComment(Request $request, $comment)
    {
        $users = $request->input('users', []);

        if (! empty($users)) {
            try {
                $this->client->post("comments/{$comment}/likes", [
                    'query' => [
                        'service' => 'post'
                    ],
                    'form_params' => [
                        'icheck_ids' => $users,
                        'is_like' => true,
                    ]
                ]);
            } catch (\Exception $exception) {
                return redirect("Error: ".$exception->getMessage());
            }
        }

        return redirect()->back();
    }

    public function allListPost(Request $request)
    {
        $query = $request->query();
        $posts = [];
        $page = Paginator::resolveCurrentPage('page');
        $limit = 24;
        $skip = $limit * ($page - 1);

        $where = [
            'deleted_at' => null
        ];

        if ($request->has('type') && in_array($request->query('type'), ['4', '2'])) {
            $where['object_type'] = $request->query('type');
        }

        if ($request->has('uid')) {
            $where['icheck_id'] = $request->input('uid');
        }

        if ($request->has('query')) {
            try {
                $response = $this->client->get('search', [
                    'query' => [
                        'type' => 'feed',
                        'query' => $request->input('query')
                    ]
                ]);

                $response = json_decode((string) $response->getBody(), true);

                if ($response['status'] != 200) {
                    return response()->json($response);
                }

                $posts = $response['data'];
            } catch (\Exception $exception) {
                return response('Error: '.$exception->getMessage(), 500);
            }
        } else {
            try {
                $response = $this->client->get('posts', [
                    'query' => [
                        'limit' => $limit,
                        'skip' => $skip,
                        'where' => json_encode($where)
                    ]
                ]);

                $response = json_decode((string) $response->getBody(), true);

                if ($response['status'] == 200) {
                    $posts = $response['data'];
                }
            } catch (\Exception $exception) {
                return response('Error: '.$exception->getMessage(), 500);
            }
        }

        return view('staff.management.fakeuser.post.list-all', compact('posts', 'query'));
    }
}
