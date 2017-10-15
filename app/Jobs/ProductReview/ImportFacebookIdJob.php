<?php

namespace App\Jobs\ProductReview;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Enterprise\ProductReview\FacebookId;
use App\Models\Enterprise\ProductReview\Group;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

class ImportFacebookIdJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $ids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ids = $this->ids;
        $accessToken = 'EAACW5Fg5N2IBADyVUuDp33v2cUUDbCAj7WLdLZCsEr8fBNpu5EiNf1JkJJPTvZCzyZContQfhJGg5OZCtRpEdP7OQK0VaRsbg04r8wnYRRjzsoQId4KcjadPT7waTnEMtcEPi0XxSyxgHdzKdADUmrFLYHKUCiXvRMW6OjX2hlUZAiJvndstaY19DnjszjDUZD';

        $deviceId = 'icheck-for-business';
        $secretKey = 'webIcheckSocial';
        $arrayNumber = [2, 8, 0, 5, 1];
        $number1 = $arrayNumber[array_rand($arrayNumber)];
        $number2 = $arrayNumber[array_rand($arrayNumber)];
        $number3 = ($number1 + $number2) % 10;
        $checkSum = str_replace("$2y$", "$2a$", bcrypt($deviceId . '|' . $secretKey) . $number1 . $number2 . $number3);

        var_dump($checkSum);

        foreach ($ids as $id) {
            $facebookId = FacebookId::firstOrCreate([
                'facebook_id' => $id,
            ]);

            if ($api = @file_get_contents('https://graph.facebook.com/v2.6/' . $id . '?access_token=' . $accessToken)) {
                $api = json_decode($api);
                $name = $api->name;
                $facebookId->update(['name' => $name]);
            }

            $groups = Group::with('members')->get()->sortBy(function ($group)
            {
                return $group->members->count();
            })->take(3)->lists('id')->toArray();

            $facebookId->groups()->sync($groups);

            $client = new Client();

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
            } catch (RequestException $e) {
                var_dump($e);
            }

            var_dump($res);
            $token = $res->data->token;

            try {
                $res = $client->request(
                    'POST',
                    config('remote.server') . '/auth/login',
                    [
                        'json' => [
                            'fb_id' => $id,
                            'name_fb' => isset($name) ? $name : $id,
                            'access_token' => 'hihahohe',
                            'sync' => '-1',
                        ],
                        'headers' => [
                            'token' => $token,
                        ],
                    ]
                );
                $res = json_decode($res->getBody());
            } catch (RequestException $e) {
                var_dump($e);
            }

            var_dump($res->data);

            sleep(3);

            foreach ($facebookId->groups as $group) {
                try {
                    $res = $client->request(
                        'POST',
                        config('remote.server') . '/groups/' . $group->icheck_id . '/join',
                        [
                            'headers' => [
                                'token' => $token,
                            ],
                        ]
                    );
                    $res = json_decode($res->getBody());
                } catch (RequestException $e) {
                    var_dump($e);
                }

                var_dump($res);
            }


        }
    }
}
