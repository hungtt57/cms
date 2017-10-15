<?php

namespace App\Http\Controllers\Staff\Management;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VirtualUserCommentController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('DOMAIN_API'),
            'auth' => [env('USER_API'), env('PASS_API')],
            'timeout'  => 3.0,
        ]);
    }

    public function add(Request $request)
    {
        $users = [];
        $limit = $request->input('quantity');

        try {
            $response = $this->client->get('users', [
                'query' => [
                    'limit' => $limit,
                    'where' => json_encode(['deleted_at' => null, 'is_virtual' => 1])
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] == 200) {
                $users = $data['data'];
            }
        } catch (\Exception $exception) {
            return response('Error', 500);
        }

        return view('staff.management.fakeuser.comment.create', compact('users'));
    }

    public function create(Request $request)
    {
        $comments = $request->input('comments', []);
        $data = [];

        foreach ($comments as $comment) {
            if ($comment['message']) {
                array_push($data, [
                    'icheck_id' => $comment['icheck_id'],
                    'object_id' => $request->input('post'),
                    'content' => $comment['message'],
                    'parent' => ''
                ]);
            };
        }

        try {
            $response = $this->client->post('comments', [
                'json' => $data,
                'query' => [
                    'service' => 'post'
                ]
            ]);

            $response = json_decode((string) $response->getBody(), true);

            if ($response['status'] != 200) {
                return response()->json($response);
            }
            
        } catch (\Exception $exception) {
            return response('Error: '.$exception->getMessage(), 500);
        }

        return redirect()->route('Staff::Management::virtualUser@post.comments.list', ['post' => $request->input('post')]);
    }
}
