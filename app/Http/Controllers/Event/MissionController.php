<?php

namespace App\Http\Controllers\Event;

use App\Http\Requests\StoreMissionRequest;
use App\Http\Requests\UpdateMissionRequest;
use App\Models\Event\MMission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MissionController extends Controller
{
    public function index()
    {
        $missions = MMission::paginate(16);

        return view('events.mission.list', compact('missions'));
    }

    public function create()
    {
        return view('events.mission.create');
    }

    public function store(StoreMissionRequest $request)
    {
        $mission = $request->only(['name', 'hook', 'description']);

        $mission['points'] = 0;

        if ($request->has('points')) {
            $mission['points'] = $request->get('points');
        }

        $mission['specialPoints'] = 0;

        if ($request->has('specialPoints')) {
            $mission['specialPoints'] = $request->get('specialPoints');
        }

        $mission['maxPerDay'] = 0;

        if ($request->has('maxPerDay')) {
            $mission['maxPerDay'] = $request->get('maxPerDay');
        }

        $mission['repeat'] = 0;

        if ($request->has('repeat')) {
            $mission['repeat'] = $request->get('repeat');
        }

        $mission['maxComplete'] = 0;

        if ($request->has('maxComplete')) {
            $mission['maxComplete'] = $request->get('maxComplete');
        }

        if ($request->has('param') && $request->has('operator') && $request->has('value')) {
            $param = $request->get('param');
            $operator = $request->get('operator');
            $value = $request->get('value');

            $conditions = [];
            $count = count($param);

            for ($i = 0; $i < $count; $i++) {
                if ($param[$i] != "" && $operator[$i] != '' && ! $value[$i] != '') {
                    array_push($conditions, [
                        'param' => $param[$i],
                        'operator' => $operator[$i],
                        'value' => $value[$i]
                    ]);
                }
            }

            if (count($conditions) > 0) {
                $mission['conditions'] = $conditions;
            }
        };

        MMission::create($mission);

        return redirect()->route('missions.list');
    }

    public function edit(MMission $mission)
    {
        return view('events.mission.edit', compact('mission'));
    }

    public function update(UpdateMissionRequest $request, MMission $mission)
    {
        $m = $request->only(['name', 'hook', 'description']);

        $mission['specialPoints'] = 0;

        if ($request->has('specialPoints')) {
            $mission['specialPoints'] = $request->get('specialPoints');
        }

        $m['maxPerDay'] = 0;

        if ($request->has('maxPerDay')) {
            $m['maxPerDay'] = $request->get('maxPerDay');
        }

        $m['repeat'] = 0;

        if ($request->has('repeat')) {
            $m['repeat'] = $request->get('repeat');
        }

        $m['maxComplete'] = 0;

        if ($request->has('maxComplete')) {
            $m['maxComplete'] = $request->get('maxComplete');
        }

        if ($request->has('param') && $request->has('operator') && $request->has('value')) {
            $param = $request->get('param');
            $operator = $request->get('operator');
            $value = $request->get('value');

            $conditions = [];
            $count = count($param);

            for ($i = 0; $i < $count; $i++) {
                if ($param[$i] != "" && $operator[$i] != '' && $value[$i] != '') {
                    array_push($conditions, [
                        'param' => $param[$i],
                        'operator' => $operator[$i],
                        'value' => $value[$i]
                    ]);
                }
            }

            if (count($conditions) > 0) {
                $m['conditions'] = $conditions;
            }
        };

        $mission->update($m);

        return back();
    }

    public function delete(MMission $mission)
    {
        $mission->delete();

        return back();
    }
}
