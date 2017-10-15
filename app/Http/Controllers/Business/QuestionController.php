<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\RequestException;
use Auth;
use Event;
use App\Models\Enterprise\DNQuestion;
use DB;
use Carbon\Carbon;
use App\Support\Hash;
use App\Models\Enterprise\DNAnswerQuestion;
class QuestionController extends Controller
{
    public function index(){
        $id = auth()->user()->id;
        $questions = DNQuestion::select('*')->where('business_id',$id);
        $questions  = $questions->paginate(20);
        return view('business.question.index',compact('questions'));
    }
    public function add(Request $request){
        return view('business.question.form');
    }
    public function store(Request $request){
        $business = auth()->user();
        $this->validate($request,[
           'title' => 'required',
            'room' => 'required',
            'service' => 'required',
            'content' => 'required',

        ],[
            'title.required' => 'Vui lòng nhập tiêu đề',
            'room.required' => 'Vui lòng chọn phòng ban',
            'service.required' => 'Vui lòng chọn dịch vụ',
            'content.required' => 'Vui lòng nhập nội dung',
        ]);

        $data = $request->all();
        $data['business_id'] = $business->id;
        $data['status'] = DNQuestion::STATUS_PENDING;
        if(isset($data['attachments'])){
            if ($request->file('attachments')->isValid()) {
                $file = $request->file('attachments');
                $filename = time() . '_' . mt_rand(0, 9999) . '_' . $file->getClientOriginalName();
                $file->move(storage_path('app/business/questions/'), $filename);
                $data['attachments'] = storage_path('app/business/questions/' . $filename);
            }
        }
        $question = DNQuestion::create($data);
        $code = new Hash();
        $code = $code->encode($question->id);
        $question->code = $code;
        $question->save();
        return redirect()->route('Business::question@index')->with('Tạo câu hỏi thành công');

    }



    public function getFile(Request $request,$id){
        $question = DNQuestion::find($id);
        return response()->download($question->attachments);
    }
    public function getAnswerQuestion(Request $request){
        $code = $request->input('code');
        $hash = new Hash();
        $id = $hash->decode($code);
        $question = DNQuestion::find($id);
        $answer_question = DNAnswerQuestion::where('question_id',$id)->orderBy('created_at','asc')->get();
        return view('business.question.ajaxListAnswer',compact('question','answer_question'));
    }

    public function addAnswerQuestion(Request $request){
        $content = $request->input('content');
        $id = $request->input('id');
        $status = $request->input('status');
        $question = DNQuestion::find($id);
        if ($status == 1) {
            $question->status = DNQuestion::STATUS_PENDING;
        } else {
            $question->status = DNQuestion::STATUS_APPROVE;
        }
        $question->save();
        $data['question_id'] = $id;
        $data['content'] = $content;
        $data['answerBy'] = DNAnswerQuestion::ANSWER_BY_BUSINESS;
        $answer = DNAnswerQuestion::create($data);
        return view('business.question.ajaxAnswer',compact('answer'));

    }
    public function changeStatus(Request $request){
        $id = $request->input('id');
        $question = DNQuestion::find($id);
        $question->status = DNQuestion::STATUS_APPROVE;
        $question->save();
        return 'oke';
    }
}
