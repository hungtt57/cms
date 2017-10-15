<?php

namespace App\Http\Controllers\Event;

use App\Http\Requests\UpdateGiftRequest;
use App\Models\Event\MGift;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GiftController extends Controller
{
    public function edit(MGift $gift)
    {
        $types = config('event.gift.type');

        return view('events.gift.edit', compact('gift', 'types'));
    }

    public function update(UpdateGiftRequest $request, MGift $gift)
    {
        $g = [];

        if ($request->has('name')) {
            $g['name'] = $request->get('name');
        }

        if ($request->has('description')) {
            $g['description'] = $request->get('description');
        }

        if ($request->has('type')) {
            $g['type'] = $request->get('type');
        }

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

                $g['image'] = $res->prefix;
            } catch (RequestException $e) {
                return back();
            }
        }

        $gift->update($g);

        return redirect()->route('events.gifts.list', $gift->eventId);
    }

    public function delete(MGift $gift)
    {
        $gift->delete();

        return back();
    }
}
