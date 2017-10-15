<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Jobs\ExportHCMJob;
use App\Models\Enterprise\GLN;
use App\Models\Icheck\Product\Product;
use App\GALib\AnalyticsLib;
use DB;
use App\Models\Enterprise\DNNotificationUser;
class RunNotificationUserBusiness extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'business:notification-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi thông báo cho doanh nghiệp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $url = 'https://helical-history-126218.firebaseio.com';
    protected $token = 'oe2Uoo0WzFYTFL5xVS1DCRHjcfiHjSJ8zzQLTZdx';
//    const DEFAULT_PATH = '/firebase/example';
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

        $firebase = new \Firebase\FirebaseLib($this->url, $this->token);
        $name = $firebase->get('/rooms-users/i-1491392977259');

       DNNotificationUser::where('status',DNNotificationUser::STATUS_APPROVE)->chunk(100,function($notifications){
           foreach ($notifications as $notification){
               $now  = Carbon::now()->getTimestamp();
               $time_send = strtotime($notification->time_send);
               if($notification->type_send == 2 and $now <= $time_send){
                   continue;
               }
                //






               //send

               $notification->status = DNNotificationUser::STATUS_FINISH;
               $notification->save();
           }
        });


    }
}
