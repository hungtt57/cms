<?php

namespace App\Http\Controllers\Event;

use App\Http\Requests\StoreEventGiftRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Requests\UpdateUserReceivingGiftRequest;
use App\Models\Event\MEvent;
use App\Models\Event\MGift;
use App\Models\Event\MMission;
use App\Models\Event\MUserReceivingGift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function index()
    {
        $events = MEvent::orderBy('startTime')->paginate(16);

        return view('events.event.list', compact('events'));
    }

    public function create()
    {
        return view('events.event.create');
    }

    public function store(StoreEventRequest $request)
    {
        $event = $request->only(['name', 'description', 'startTime', 'endTime']);

        $event['startTime'] = Carbon::createFromFormat('Y-m-d\TH:i', $event['startTime']);
        $event['endTime'] = Carbon::createFromFormat('Y-m-d\TH:i', $event['endTime']);

        $giftExchange = false;

        if ($request->get('giftExchange') == 1 || $request->get('giftExchange') == 'true') {
            $giftExchange = true;
        }

        $event['giftExchange'] = $giftExchange;

        $client = new \GuzzleHttp\Client();

        $validator = Validator::make([], []);

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

            $validator->after(function($validator) {
                $validator->errors()->add('image', 'Error upload image');
            });

            return back()->withErrors($validator)->withInput();
        }

        $event['image'] = $res->prefix;

        $event = MEvent::create($event);

        return redirect('/events');
    }

    public function show(MEvent $event)
    {
        return $event;
    }

    public function edit(MEvent $event)
    {
        return view('events.event.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, MEvent $event)
    {
        $e = [];

        if ($request->has('name')) {
            $e['name'] = $request->get('name');
        }

        if ($request->has('description')) {
            $e['description'] = $request->get('description');
        }

        if ($request->has('startTime')) {
            $e['startTime'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->get('startTime'));
        }

        if ($request->has('endTime')) {
            $e['endTime'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->get('endTime'));
        }

        if ($request->has('giftExchange')) {
            $giftExchange = false;

            if ($request->get('giftExchange') == 1 || $request->get('giftExchange') == 'true') {
                $giftExchange = true;
            }

            $e['giftExchange'] = $giftExchange;
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
            } catch (RequestException $e) {
                return back();
            }

            $e['image'] = $res->prefix;
        }

        $event->update($e);

        return redirect('/events')->with('message', 'Đã cập nhật sự kiện');
    }

    public function delete(MEvent $event)
    {
        $event->delete();

        return back();
    }

    public function createGift(MEvent $event)
    {
        $types = config('event.gift.type');

        return view('events.event.createGift', compact('event', 'types'));
    }

    public function storeGift(StoreEventGiftRequest $request, MEvent $event)
    {
        $gifts = [];
        $gift = $request->only(['name', 'description', 'type']);

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
            return back();
        }

        $gift['image'] = $res->prefix;

        $count = $request->get('count');

        for ($i = 0; $i < $count; $i++) {
            array_push($gifts, new MGift($gift));
        }

        $event->gifts()->saveMany($gifts);

        return redirect()->route('events.gifts.list', $event->id);
    }

    public function gift(MEvent $event)
    {
        $gifts = $event->gifts()->paginate(16);
        $types = config('event.gift.type');

        return view('events.event.gift', compact('event', 'gifts', 'types'));
    }

    public function mission(MEvent $event)
    {
        $missionIds = [];

        if ($event->missions) {
            $missionIds = $event->missions;
        }

        $missions = MMission::whereIn('_id', $missionIds)->paginate(16);

        return view('events.event.mission', compact('event', 'missions'));
    }

    public function addMission(MEvent $event)
    {
        $missionIds = [];

        if ($event->missions) {
            $missionIds = $event->missions;
        }

        $missions = MMission::whereNotIn('_id', $missionIds)->paginate(16);

        return view('events.event.addMission', compact('event', 'missions'));
    }

    public function storeMission(Request $request, MEvent $event)
    {
        $missionIds = $request->get('mission', []);
        $missionIds = MMission::whereIn('_id', $missionIds)->pluck('_id')->toArray();

        if (count($missionIds) > 0) {
            $event->push('missions', $missionIds, true);
        }

        return redirect()->route('events.missions.list', $event->id);
    }

    public function removeMission(MEvent $event, MMission $mission)
    {
        $event->pull('missions', $mission->id);

        return back();
    }

    public function userReceivingGift(MEvent $event)
    {
        $gifts = $event->gifts()->with(['userReceive', 'receiver'])->where('receiverId', 'exists', true)->paginate(20);

        return view('events.event.userReceivingGift', compact('gifts'));
    }
}
