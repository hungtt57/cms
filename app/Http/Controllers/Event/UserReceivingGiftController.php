<?php

namespace App\Http\Controllers\Event;

use App\Http\Requests\UpdateUserReceivingGiftRequest;
use App\Models\Event\MUserReceivingGift;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserReceivingGiftController extends Controller
{
    public function edit(MUserReceivingGift $userReceive)
    {
        return view('events.userreceive.edit', compact('userReceive'));
    }

    public function update(UpdateUserReceivingGiftRequest $request, MUserReceivingGift $userReceive)
    {
        $userReceive->update($request->only(['name', 'phone', 'email', 'address', 'status']));

        return redirect()->route('events.userreceivinggift.list', $userReceive->gift->event->id);
    }
}
