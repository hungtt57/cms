<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enterprise\ProductReview\FacebookId;
use App\Models\Enterprise\ProductReview\Group;
use App\Models\Social\Account;
use App\Models\Social\MRealtime;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class UserFollow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-follow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        FacebookId::with('groups')->chunk(20, function ($fbs) {
            foreach ($fbs as $fb) {
                $this->info($fb->facebook_id);
                $client = new Client();

                $deviceId = 'icheck-for-business';
                $secretKey = 'webIcheckSocial';
                $arrayNumber = [2, 8, 0, 5, 1];
                $number1 = $arrayNumber[array_rand($arrayNumber)];
                $number2 = $arrayNumber[array_rand($arrayNumber)];
                $number3 = ($number1 + $number2) % 10;
                $checkSum = str_replace("$2y$", "$2a$", bcrypt($deviceId . '|' . $secretKey) . $number1 . $number2 . $number3);

                try {
                    $res = $client->request(
                        'POST',
                        config('remote.server') . '/auth/token',
                        [
                            'json' => [
                                'device_id' => $deviceId,
                                'app_os' => 'web',
                            ],
                            'headers' => [
                                'check_sum' => $checkSum,
                            ],
                        ]
                    );
                    $res = json_decode($res->getBody());
                } catch (\Exception $e) {
                }

                $token = $res->data->token;
                echo $token;

                try {
                    $res = $client->request(
                        'POST',
                        config('remote.server') . '/auth/login',
                        [
                            'json' => [
                                'fb_id' => $fb->facebook_id,
                                'name_fb' => $fb->name ?: $fb->facebook_id,
                                'access_token' => 'hihahohe',
                                'sync' => '-1',
                            ],
                            'headers' => [
                                'token' => $token,
                            ],
                        ]
                    );
                    $res = json_decode($res->getBody());
                } catch (\Exception $e) {
                }

                sleep(3);

                foreach ($fb->groups as $group) {
                    $ids = $group->members->lists('facebook_id')->toArray();
                    $accounts = Account::whereIn('account', $ids)->get();

                    foreach ($accounts as $account) {
                        $this->info($account->icheck_id);

                        try {
                            $res = $client->request(
                                'POST',
                                config('remote.server') . '/users/follow',
                                [
                                    'headers' => [
                                        'token' => $token,
                                    ],
                                    'json' => [
                                        'follow_id' => $account->icheck_id,
                                        'follow' => 1,
                                    ],
                                ]
                            );
                            $res = json_decode($res->getBody());
                        } catch (\Exception $e) {
                        }

                        var_dump($res);
                    }
                }
            }
        });
    }
}
