<?php

namespace App\Remote;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

class Remote
{
    protected $services = [];

    protected $server;

    public function __construct($config)
    {
        $this->server = $config['server'];
    }

    public function service($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        $className = __NAMESPACE__ . '\\' . studly_case($name) . 'Service';

        if (class_exists($className)) {
            return new $className($this);
        }

        throw new InvalidArgumentException("Unsupported service $name");
    }

    public function request($method, $path, $data = [])
    {
        $client = new Client();
        $options = [];

        if ($data) {
            $options['json'] = $data;
        }

        try {
            $res = $client->request(
                $method,
                $this->server . $path,
                $options
            );
            $res = json_decode($res->getBody())->data;
        } catch (RequestException $e) {
            var_dump($e);
            return $e->getResponse()->getBody();
        }

        return $res;
    }
}
