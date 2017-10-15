<?php

namespace App\Http\Controllers\Staff\Management;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VirtualUserLikeController extends Controller
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

    public function likePost(Request $request, $post)
    {
        $limit = $request->input('quantity');
        $users = [];

        try {
            $response = $this->client->get('users', [
                'query' => [
                    'limit' => $limit,
                    'where' => json_encode(['deleted_at' => null, 'is_virtual' => 1])
                ]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($data['status'] != 200) {
                return response()->json($data);
            }

            $users = $data['data'];
        } catch (\Exception $exception) {
            return response('Error: '.$exception->getMessage(), 500);
        }

        $userIds = array_map(function ($user) {
            return $user['icheck_id'];
        }, $users);

        try {
            $response = $this->client->post("posts/{$post}/likes", [
                'form_params' => [
	                "icheck_ids" => $userIds,
	                "is_like" => true
                ]
            ]);

            $response = json_decode((string) $response->getBody(), true);

            if ($response['status'] != 200) {
                return response()->json($response);
            }
        } catch (\Exception $exception) {
            return response('Error: '.$exception->getMessage(), 500);
        }

        return redirect()->back();
    }

    public function ajaxLikePost(Request $request, $post)
    {
        $user = $request->input('lid');

        try {
            $response = $this->client->post("posts/{$post}/likes", [
                'form_params' => [
                    "icheck_ids" => [$user],
                    "is_like" => true
                ]
            ]);

            $response = json_decode((string) $response->getBody(), true);

            return response()->json($response);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => [
                    'message' => $exception->getMessage()
                ]
            ], 500);
        }
    }
}
