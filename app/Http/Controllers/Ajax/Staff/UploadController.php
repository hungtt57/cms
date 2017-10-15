<?php

namespace App\Http\Controllers\Ajax\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Response;
class UploadController extends Controller
{
    public function image(Request $request)
    {
        if ($request->input('via_url') == 1) {
            $fname = time() . mt_rand(0, 9999);
            $c = @file_get_contents($request->input('url'));

            if (!$c) {
                return response('', 400);
            }

            @file_put_contents(storage_path('app/' . $fname), $c);

            if (!getimagesize(storage_path('app/' . $fname))) {
                return response('', 400);
            }

            $client = new Client();

            try {
                $res = $client->request(
                    'POST',
                    'http://upload.icheck.vn/v1/images?uploadType=simple',
                    [
                        'body' => file_get_contents(storage_path('app/' . $fname)),
                    ]
                );
                $res = json_decode((string) $res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            @unlink(storage_path('app/' . $fname));

            return ['prefix' => $res->prefix, 'url' => get_image_url($res->prefix)];
        }
        $this->validate($request, [
            'file' => 'image',
        ]);

        $size = filesize($request->file('file'));

        if(intval($size) < 20480){
            return Response::json(['error' => 'Dung lượng ảnh tối thiểu 20KB'], 404); // Status code here
        }

        $client = new Client();

        try {
            $res = $client->request(
                'POST',
                'http://upload.icheck.vn/v1/images?uploadType=simple',
                [
                    'body' => file_get_contents($request->file('file')),
                ]
            );
            $res = json_decode((string) $res->getBody());
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }

        return ['prefix' => $res->prefix, 'url' => get_image_url($res->prefix)];
    }

}
