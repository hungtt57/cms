<?php

namespace App\Remote;

use App\Models\Enterprise\GLN;

class ProductService
{
    protected $remote;

    public function __construct($remote)
    {
        $this->remote = $remote;
    }

    public function sync(GLN $object)
    {
        $data = [
            'name' => $object->name,
            'country' => $object->country->id,
            'address' => $object->address,
        ];

        return $this->remote->request('PUT', '/web/vendors/' . $object->gln, $data);
    }

    public function get($gtin)
    {
        return $this->remote->request('GET', '/web/products/' . $gtin);
    }
}
