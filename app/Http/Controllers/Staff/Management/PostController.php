<?php


namespace App\Http\Controllers\Staff\Management;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Enterprise\Post;
use App\Models\Mongo\Social\Feed;
use Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use DB;
use App\Models\Mongo\Social\Comment;
use App\Models\Icheck\User\Account;
use App\Models\Icheck\Social\Post as SPost;
use App\Models\Icheck\Social\News as SNew;
use App\Models\Icheck\Social\Category;
use App\Models\Icheck\Social\CategoryPost;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-post')) {
            abort(403);
        }
//        DB::connection('social_mongodb')->enableQueryLog();

        if ($request->input('search')) {
            $posts = Post::where('title', 'like', '%' . $request->input('search') . '%')
                ->where('version', '=', 1)
                ->paginate(15);
        } else {
            $posts = Post::orderBy('created_at', "DESC")->where('version', '=', 1)->where('deleted',0)->paginate(15);
        }
        $list_accounts = config('listIcheckId');
        return view('staff.management.post.index', compact('posts','list_accounts'));
    }

    public function add()
    {
        if (auth()->guard('staff')->user()->cannot('add-post')) {
            abort(403);
        }
        $categories = Category::all();
        return view('staff.management.post.form',compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-post')) {
            abort(403);
        }
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'image' => 'required|image',
            'source' => 'required',
            'tag' => 'required',
        ]);

        $data = $request->all();
        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request(
                'POST',
                'http://upload.icheck.vn/v1/images?uploadType=simple',
                [
                    'body' => file_get_contents($request->file('image')),
                ]
            );
            $res = json_decode((string)$res->getBody());
        } catch (RequestException $e) {
            return $e->getResponse()->getBody();
        }
        $data['image'] = $res->prefix;
        $data['version'] = 1; //new version
        $post = Post::create($data);
        $post->save();
        if($request->input('categories')){
            $categories = $request->input('categories');
            foreach ($categories as $category){
                CategoryPost::firstOrCreate(['post_id'=> $post->id,'category_id' => $category]);
            }
        }
        return redirect()->route('Staff::Management::post@index')
            ->with('success', 'Đã thêm tin tức');

    }

    public function edit($id)
    {
        if (auth()->guard('staff')->user()->cannot('edit-post')) {
            abort(403);
        }
        $post = Post::findOrFail($id);
        $categories = Category::all();
        $selectedCategories = CategoryPost::where('post_id',$id)->get()->lists('category_id')->toArray();
        return view('staff.management.post.form', compact('post','categories','selectedCategories'));
    }

    public function update($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('edit-post')) {
            abort(403);
        }
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'source' => 'required',
            'tag' => 'required',
        ]);
        $post = Post::findOrFail($id);
        $data = $request->all();
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
                $res = json_decode((string)$res->getBody());
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $data['image'] = $res->prefix;
        }


        if ($post->icheck_id) {
            $s_post = SPost::find($post->icheck_id);
            $s_new = SNew::find($s_post->object_id);
            if($s_new){
                $post->update($data);
                $post->save();
                $s_new->title = $post->title;
                $s_new->thumb = $post->image;
                $s_new->content = $post->content;
                $s_new->description = $post->description;
                $s_new->source = $post->source;
                $s_new->save();
            }else{
                return redirect()->back()->with('error','Có lỗi xảy ra không tìm thấy s_news');
            }

        }else{
            $post->update($data);
        }
        if($request->input('categories')){
            $categories = $request->input('categories');
            CategoryPost::where('post_id',$post->id)->whereNotIn('category_id',$categories)->delete();
            foreach ($categories as $category){
                CategoryPost::firstOrCreate(['post_id'=> $post->id,'category_id' => $category]);
            }
        }else{
            CategoryPost::where('post_id',$post->id)->delete();
        }
        return redirect()->route('Staff::Management::post@index', $post->id)
            ->with('success', 'Đã cập nhật tin tức');
    }

    public function delete($id)
    {
        if (auth()->guard('staff')->user()->cannot('delete-post')) {
            abort(403);
        }
        $post = Post::findOrFail($id);

        if ($post->icheck_id) {
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->delete(env('DOMAIN_API').'posts/' . $post->icheck_id, [
                    'auth' => [env('USER_API'), env('PASS_API')],
                ]);

                $res = json_decode((string)$res->getBody());
                if ($res->status != 200) {
                    return redirect()->back()->with('error', 'Server bị lỗi! Vui lòng thử lại sau');
                }
                $post->deleted = 1;
                $post->save();
//                $post->delete();
                return redirect()->back()->with('success', 'Xóa thành công!');

            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'Server bị lỗi! Vui lòng thử lại sau');

            }
        }


        return redirect()->route('Staff::Management::post@index')->with('success', 'Đã xoá thành công');;
    }

    public function approve($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('add-post')) {
            abort(403);
        }
        $post = Post::findOrFail($id);
        $icheck_id = $request->input('icheck_id');

        if(empty($icheck_id)){
            return redirect()->back()->with('success','Vui Lòng chọn Tài khoản của publish tin');
        }
        if($request->input('publishNow')){
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('POST', env('DOMAIN_API') . 'posts/', [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'news' => [
                            'title' => $post->title,
                            'thumb' => $post->image,
                            'description' => $post->description,
                            'content' => $post->content,
                        ],
                        'owner' => ['icheck_id' => $icheck_id],
                        'to' => ['all'],
                        'disable_push' => true
                    ],
                ]);
                $res = json_decode((string)$res->getBody());
                if ($res->status == 200 ) {
                    $post->icheck_id = $res->data->id;
                    $post->publishBy = $icheck_id;
                    $post->save();
                    $s_post = SPost::find($post->icheck_id);
                    if($s_post){
                        $res = $client->request('POST', env('DOMAIN_API') . 'notifications/send', [
                            'auth' => [env('USER_API'), env('PASS_API')],
                            'form_params' => [
                                'object_type' => $s_post->object_type,
                                'object_id' => $s_post->object_id,
                                'message' => $post->title
                            ],
                        ]);
                        $res = json_decode((string)$res->getBody());
                        if ($res->status == 200 ) {
                            return redirect()->back()->with('success', 'Đã publish tin tức và thông báo cho toàn bộ user');
                        }else{
                            return redirect()->back()->with('error', 'Đã publish tin tức nhưng thông báo cho toàn bộ user lỗi');
                        }
                    }else{
                        return redirect()->back()->with('error', 'publish thành công nhưng không tồn tại s_post');
                    }

                } else {
                    return redirect()->back()->with('error', 'Loi khi publish tin tức');
                }
            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }
        }else{
            if($request->input('publishTime')){
                $publishTime = $request->input('publishTime');
                $publishTime = strtotime($publishTime);
                $post->publishTime = $publishTime;
                $post->publishBy = $icheck_id;
                $post->save();
                return redirect()->back()->with('success', 'Đã lên lịch publish tin tức');
            }else{
                return redirect()->back()->with('error', 'Vui lòng chọn hình thức publish');
            }

        }



    }

    public function comments($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-post-comment')) {
            abort(403);
        }
        $post = Post::find($id);
        $comments = null;

        if ($post->postIcheck) {
            $comments = Comment::where('object_id', $post->postIcheck->id)->where('parent', '')->where('deleted_at', null)->orderBy('createdAt', 'desc')->paginate(20);
        }
        $account = Account::where('icheck_id', 'i-1469083968959')->first();
        return view('staff.management.post.comments', compact('comments', 'post', 'account'));
    }

    public function answerComment(Request $request)
    {

        if (auth()->guard('staff')->user()->cannot('list-post-answer-comment')) {
            abort(403);
        }
        $icheck_id = 'i-1469083968959';
        if ($icheck_id) {
            $account = Account::where('icheck_id', $icheck_id)->first();
            $content = $request->input('content');
            $parent_id = $request->input('parent_id');
            $post_id = $request->input('icheck_id');
            if (trim($content) == '') {
                return response()->json(['message' => 'Vui lòng nhập nội dung'], 400);
            }
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('POST', env('DOMAIN_API') . 'comments', [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'icheck_id' => $icheck_id,
                        'object_id' => $post_id,
                        'content' => $content,
                        'parent' => $parent_id,
                        'service' => 'post'
                    ]
                ]);

                $res = json_decode((string)$res->getBody());

            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $template = ' <div class="media"><div class="media-left">';
            if (isset($res->data->owner->social_id)) {
                $template .= '  <img src="http://graph.facebook.com/' . $res->data->owner->social_id . '/picture"
                                                                 class="img-circle" alt=""> ';
            } else {
                $template .= '<img src="' . public_path("assets/images/image.png") . 'class="img-circle" alt="">';
            }

            $template .= '</div><div class="media-body"><h6 class="media-heading"><strong class="js-actor-name">' . $account->name . '</strong></h6> <p class="js-comment-content">' . convertTextToLink($content) . '</p>';

            $template .= '<div class="media-annotation mt-5 js-action-time">   <div class="col-md-3">' . Carbon::createFromTimestamp(round($res->data->createdAt / 1000))->toDateTimeString() . '</div><div class="col-md-9 answer"> <button type="button" class="btn text-slate-800 btn-flat button-delete" data-url="' .
                route("Staff::Management::post@deleteComment", ["id" => $res->data->id]) . '" data-id="' . $res->data->id . '">Xóa<span class="legitRipple-ripple"></span></button></div>
                <div style="clear:both"></div></div></div></div>';
            return $template;
        }
    }


    public function addComment(Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-post-add-comment')) {
            abort(403);
        }
        $icheck_id = 'i-1469083968959';
        if ($icheck_id) {
            $account = Account::where('icheck_id', $icheck_id)->first();
            $content = $request->input('content');
            $post_id = $request->input('icheck_id');

            if (trim($content) == '') {
                return response()->json(['message' => 'Vui lòng nhập nội dung'], 400);
            }
            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->request('POST', env('DOMAIN_API') . 'comments', [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'icheck_id' => $icheck_id,
                        'object_id' => $post_id,
                        'content' => $content,
                        'parent' => '',
                        'service' => 'post'
                    ]
                ]);

                $res = json_decode((string)$res->getBody());

            } catch (RequestException $e) {
                return $e->getResponse()->getBody();
            }

            $comment = $res->data;
            $comment = Comment::where('_id', $comment->id)->first();
            return view('staff.management.post.ajaxComment', compact('comment', 'account'))->render();
        }
    }

    public function deleteComment($id, Request $request)
    {
        if (auth()->guard('staff')->user()->cannot('list-post-delete-comment')) {
            abort(403);
        }
        $comment = Comment::where('_id', $id)->first();
        $icheck_id = 'i-1469083968959';
        if ($comment) {

            $iComment = $comment->owner['icheck_id'];
//            if ($icheck_id != $iComment) {
//                return redirect()->back()->with('error', 'Bạn không có quyền xóa comment này');
//            }

            $client = new \GuzzleHttp\Client();
            try {
                $res = $client->delete(env('DOMAIN_API') . 'comments/' . $id, [
                    'auth' => [env('USER_API'), env('PASS_API')],
                    'form_params' => [
                        'service' => 'post'
                    ]
                ]);

                $res = json_decode((string)$res->getBody());
                if ($res->status != 200) {
                    return redirect()->back()->with('error', 'Server bị lỗi! Vui lòng thử lại sau');
                }
                return redirect()->back()->with('success', 'Xóa thành công!');
            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'Server bị lỗi! Vui lòng thử lại sau');

            }
        } else {
            return redirect()->back()->with('error', 'Comment không tồn tại');
        }
    }
}
