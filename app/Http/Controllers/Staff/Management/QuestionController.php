<?php


namespace App\Http\Controllers\Staff\Management;

use App\Models\Enterprise\DNAnswerQuestion;
use App\Support\Hashids;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use DB;
use App\Models\Enterprise\DNQuestion;
use App\Support\Hash;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $questions = DNQuestion::select('*');
        if ($request->input('title')) {
            $questions = $questions->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if ($request->input('status')) {
            $questions = $questions->where('status', $request->input('status'));
        }
        $questions = $questions->orderBy('created_at', 'desc')->paginate(10);
        return view('staff.management.question.index', compact('questions'));
    }

    public function getFile(Request $request, $id)
    {
        $question = DNQuestion::find($id);
        return response()->download($question->attachments);
    }

    public function getAnswerQuestion(Request $request)
    {
        $code = $request->input('code');
        $hash = new Hash();
        $id = $hash->decode($code);
        $question = DNQuestion::find($id);
        $answer_question = DNAnswerQuestion::where('question_id', $id)->orderBy('created_at', 'asc')->get();
        return view('staff.management.question.ajaxListAnswer', compact('question', 'answer_question'));
    }

    public function addAnswerQuestion(Request $request)
    {
        $content = $request->input('content');
        $id = $request->input('id');
        $status = $request->input('status');
        $question = DNQuestion::find($id);
        if ($status == 1) {
            $question->status = DNQuestion::STATUS_REQUIRE_BUSINESS;
        } else {
            $question->status = DNQuestion::STATUS_ANSWERED;
        }
        $question->save();
        $data['question_id'] = $id;
        $data['content'] = $content;
        $data['answerBy'] = DNAnswerQuestion::ANSWER_BY_ICHECK;
        $answer = DNAnswerQuestion::create($data);
        return view('staff.management.question.ajaxAnswer', compact('answer'));

    }
}
