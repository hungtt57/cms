<?php


namespace App\Http\Controllers\Staff\Management;

//use App\Models\Social\Message;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;

use App\Models\Icheck\Product\Message;
class MessageController extends Controller
{
    public function index(Request $request){

        if (auth()->guard('staff')->user()->cannot('list-message')) {
            abort(403);
        }

        if ($request->input('search')){
            $messages = Message::where('short_msg', 'like', '%' . $request->input('search') . '%')->paginate(15);
        }
        else{
            $messages = Message::paginate(15);
        }

        return view('staff.management.message.index',compact('messages'));
    }

    public function add()
    {

        if (auth()->guard('staff')->user()->cannot('add-message')) {
            abort(403);
        }
        return view('staff.management.message.form');
    }

    public function store(Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('add-message')) {
            abort(403);
        }
        $this->validate($request, [
            'short_msg' =>'required',
            'full_msg' =>'required',

        ]);

        $data = $request->all();

        $message = Message::create($data);
        $message->save();

        return redirect()->route('Staff::Management::message@index')
            ->with('success', 'Đã thêm');

    }

    public function edit($id)
    {

        if (auth()->guard('staff')->user()->cannot('edit-message')) {
            abort(403);
        }

        $message = Message::findOrFail($id);
        return view('staff.management.message.form',compact('message'));
    }

    public function update($id,Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-message')) {
            abort(403);
        }

        $this->validate($request, [
            'short_msg' =>'required',
            'full_msg' =>'required',

        ]);
        $message = Message::findOrFail($id);
        $data = $request->all();

        $message->update($data);
        $message->save();
        return redirect()->route('Staff::Management::message@index',$message->id)
            ->with('success', 'Đã cập nhật');
    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-message')) {
            abort(403);
        }
        $message = Message::findOrFail($id);
        $message->delete();
        return redirect()->route('Staff::Management::message@index')->with('success', 'Đã xoá thành công');
    }


}
