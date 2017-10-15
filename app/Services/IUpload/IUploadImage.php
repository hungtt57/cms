<?php

namespace App\Services\IUpload;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\UploadedFile;

class IUploadImage
{
    private $client; 
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function upload(UploadedFile $file)
    {
        try {
            $response = $this->client->post(
                'http://upload.icheck.vn/v1/images?uploadType=simple',
                [
                    'body' => file_get_contents($file)
                ]
            );

            $response = json_decode((string) $response->getBody());

            return $response->prefix;
        } catch (RequestException $e) {
            return '';
        }
    }
}