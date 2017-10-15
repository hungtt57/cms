<?php

namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\GLN;
use App\Models\Enterprise\Product;
use App\Models\Enterprise\ProductCategory;
use App\Models\Enterprise\MICheckReport;


use App\Models\Social\Message;

use App\Models\Mongo\Product\PProduct;
use App\Models\Mongo\Product\PComment as Comment;
use App\Models\Icheck\Product\Product as SProduct;
use GuzzleHttp\Exception\RequestException;
use Auth;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-comment')) {
            abort(403);
        }

        if ($request->has('gtin')) {

            $comments = Comment::where('object_id','=', $request->input('gtin'))->where('parent','=','')->orderBy('createdAt', 'desc')->simplePaginate(30);
        } else {
            $comments = Comment::orderBy('createdAt', 'desc')->simplePaginate(30);
        }

        return view('staff.management.comment.index', compact('comments'));
    }

    public function batch(Request $request)
    {
        $ids = $request->input('selected');
        Comment::whereIn('_id', $ids)->orWhereIn('parent', $ids)->delete();

        return redirect()->back()
            ->with('success', 'Đã xoá thành công');
    }
}
