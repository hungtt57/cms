<?php

namespace App\Jobs\ProductReview;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Remote\Remote;
use App\Models\Enterprise\ProductReview\Product;
use App\Models\Enterprise\ProductReview\Group;
use App\Models\Enterprise\ProductReview\Review;
use App\Models\Enterprise\ProductReview\FacebookId;
//use App\Models\Social\Product as SicialProduct;
use App\Models\Icheck\Product\Product as SicialProduct;
//use App\Models\Social\ProductAttr;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Social\MAccount;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

class ApproveReviewJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $review;
    protected $note;
    protected $amount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($review, $note, $amount, $staff)
    {
        $review->update([
            'price' => $amount,
            'status' => Review::STATUS_IN_PROGRESS,
            'approved_at' => Carbon::now(),
        ]);
        $review->approvedBy()->associate($staff);
        $review->save();

        $this->review = $review;
        $this->note = $note;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $review = $this->review;
        $product = SicialProduct::where('gtin_code', $review->gtin)->first();

        // If product has no category
        if (!$product->categories) {
            $review->update([
                'error_message' => 'Sản phẩm được review không thuộc bất cứ danh mục nào',
                'status' => Review::STATUS_ERROR,
            ]);

            return;
        }

        $categories = $product->categories->lists('id')->toArray();
        $groups = collect([]);

        foreach ($categories as $catId) {
            $groups = Group::whereRaw('(`max_review` = 0 OR `review_count` < `max_review`)')
                ->where('categories', 'LIKE', '%"' . $catId . '"%')
                ->orderBy('review_count', 'asc')
                ->get();

            if ($groups->count()) {
                break;
            }
        }

        // Post to wall
        //if (!$groups->count()) {
            $facebook = FacebookId::orderByRaw("RAND()")->first();

            if (!$facebook) {
                $review->update([
                    'error_message' => 'Không có Facebook ID nào có thể sử dụng',
                    'status' => Review::STATUS_ERROR,
                ]);

                return;
            }

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
                $review->update([
                    'error_message' => $e->getMessage(),
                    'status' => Review::STATUS_ERROR,
                ]);

                return;
            }

            $token = $res->data->token;

            try {
                $res = $client->request(
                    'POST',
                    config('remote.server') . '/auth/login',
                    [
                        'json' => [
                            'fb_id' => $facebook->facebook_id,
                            'name_fb' => $facebook->name ?: $facebook->facebook_id,
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
                $review->update([
                    'error_message' => $e->getMessage(),
                    'status' => Review::STATUS_ERROR,
                ]);

                return;
            }

            sleep(3);

            $account = MAccount::where('facebook_id', $facebook->facebook_id)->first();

            if (is_null($account)) {
                $review->update([
                    'error_message' => 'Không có Account tương ứng nào có thể sử dụng',
                    'status' => Review::STATUS_ERROR,
                ]);

                return;
            }

            $followings = $account->getAttribute('following');
            $followers = $account->getAttribute('followers');
            $friends = array_merge($followings, $followers, ['me']);
            $friends = ['me'];

            try {
                $res = $client->request(
                    'POST',
                    config('remote.server') . '/posts',
                    [
                        'headers' => [
                            'token' => $token,
                        ],
                        'json' => [
                            'type' => 'product',
                            'target_type' => 'user',
                            'target_id' => $friends[array_rand($friends)],
                            'gtin' => $review->gtin,
                            'message' => $review->content,
                        ],
                    ]
                );
                $res = json_decode($res->getBody());
            } catch (\Exception $e) {
                $review->update([
                    'error_message' => $e->getMessage(),
                    'status' => Review::STATUS_ERROR,
                ]);

                return;
            }
        // } else {
        //     foreach ($groups as $group) {
        //         $facebook = $group->members()->orderByRaw("RAND()")->first();

        //         if (!$facebook) {
        //             continue;
        //         }

        //         $client = new Client();
        //         $deviceId = 'icheck-for-business';
        //         $secretKey = 'webIcheckSocial';
        //         $arrayNumber = [2, 8, 0, 5, 1];
        //         $number1 = $arrayNumber[array_rand($arrayNumber)];
        //         $number2 = $arrayNumber[array_rand($arrayNumber)];
        //         $number3 = ($number1 + $number2) % 10;
        //         $checkSum = str_replace("$2y$", "$2a$", bcrypt($deviceId . '|' . $secretKey) . $number1 . $number2 . $number3);

        //         try {
        //             $res = $client->request(
        //                 'POST',
        //                 config('remote.server') . '/auth/token',
        //                 [
        //                     'json' => [
        //                         'device_id' => $deviceId,
        //                         'app_os' => 'web',
        //                     ],
        //                     'headers' => [
        //                         'check_sum' => $checkSum,
        //                     ],
        //                 ]
        //             );
        //             $res = json_decode($res->getBody());
        //         } catch (\Exception $e) {
        //             $review->update([
        //                 'error_message' => $e->getMessage(),
        //                 'status' => Review::STATUS_ERROR,
        //             ]);

        //             return;
        //         }

        //         $token = $res->data->token;

        //         try {
        //             $res = $client->request(
        //                 'POST',
        //                 config('remote.server') . '/auth/login',
        //                 [
        //                     'json' => [
        //                         'fb_id' => $facebook->facebook_id,
        //                         'name_fb' => $facebook->name ?: $facebook->facebook_id,
        //                         'access_token' => 'hihahohe',
        //                         'sync' => '-1',
        //                     ],
        //                     'headers' => [
        //                         'token' => $token,
        //                     ],
        //                 ]
        //             );
        //             $res = json_decode($res->getBody());
        //         } catch (\Exception $e) {
        //             $review->update([
        //                 'error_message' => $e->getMessage(),
        //                 'status' => Review::STATUS_ERROR,
        //             ]);

        //             return;
        //         }

        //         sleep(3);

        //         try {
        //             $res = $client->request(
        //                 'POST',
        //                 config('remote.server') . '/posts',
        //                 [
        //                     'headers' => [
        //                         'token' => $token,
        //                     ],
        //                     'json' => [
        //                         'type' => 'product',
        //                         'target_type' => 'group',
        //                         'target_id' => $group->icheck_id,
        //                         'gtin' => $review->gtin,
        //                         'message' => $review->content,
        //                     ],
        //                 ]
        //             );
        //             $res = json_decode($res->getBody());
        //         } catch (\Exception $e) {
        //             $review->update([
        //                 'error_message' => $e->getMessage(),
        //                 'status' => Review::STATUS_ERROR,
        //             ]);

        //             return;
        //         }

        //         $group->increment('review_count');

        //         break;
        //     }

        //     if (!$facebook) {
        //         $review->update([
        //             'error_message' => 'Không có Facebook ID nào có thể sử dụng',
        //             'status' => Review::STATUS_ERROR,
        //         ]);

        //         return;
        //     }
        // }

        $review->update([
            'note' => $this->note,
            'status' => Review::STATUS_APPROVED,
        ]);

        $review->reviewer()->increment('balance', $this->amount);
    }
}
