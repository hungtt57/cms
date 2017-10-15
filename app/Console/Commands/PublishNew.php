<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enterprise\MICheckReport;
use Carbon\Carbon;
use App\Jobs\ReportData;
use App\Models\Enterprise\Post;
use App\Models\Icheck\Social\Post as SPost;
class PublishNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish-new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish tin tuc';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = Carbon::now()->getTimestamp();
        $posts = Post::where('icheck_id',null)->where('publishTime','>',0)->where('publishTime','<=',$time)->get();
        foreach ($posts as $post){

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
                        'owner' => ['icheck_id' => $post->publishBy],
                        'to' => ['all'],
                        'disable_push' => true
                    ],
                ]);
                $res = json_decode((string)$res->getBody());
                if ($res->status == 200 ) {
                    $post->icheck_id = $res->data->id;
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
                        }
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

    }
}
