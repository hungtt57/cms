<?php
namespace App\GALib;
use Carbon\Carbon;
use Spatie\Analytics\AnalyticsClient as AnalyticsClient;
use Google_Client;
use Google_Service_Analytics;
use Illuminate\Contracts\Cache\Repository;
class AnalyticsClientLib extends AnalyticsClient
{
    public function __construct()
    {

        $config = config('laravel-analytics');
        $client = new Google_Client();

        $client->setClassConfig(
            'Google_Cache_File',
            'directory',
            $config['cache_location'] ?? storage_path('app/laravel-google-analytics/google-cache/')
        );

        $credentials = $client->loadServiceAccountJson(
            $config['service_account_credentials_json'],
            'https://www.googleapis.com/auth/analytics.readonly'
        );

        $client->setAssertionCredentials($credentials);

        $google_service = new Google_Service_Analytics($client);
        parent::__construct($google_service, app(Repository::class));
    }

    public function performRealTimeQuery(string $viewId, string $metrics, array $others = [])
    {
        $cacheName = $this->determineCacheName(func_get_args());

        if ($this->cacheLifeTimeInMinutes == 0) {
            $this->cache->forget($cacheName);
        }

        return $this->service->data_realtime->get(
            "ga:{$viewId}",
            $metrics,
            $others
        );

    }

    public function query(string $viewId, Carbon $startDate, Carbon $endDate, string $metrics, array $others = [])
    {
        $cacheName = $this->determineCacheName(func_get_args());

        if ($this->cacheLifeTimeInMinutes == 0) {
            $this->cache->forget($cacheName);
        }

        return $this->cache->remember($cacheName, $this->cacheLifeTimeInMinutes, function () use ($viewId, $startDate, $endDate, $metrics, $others) {
            return $this->service->data_ga->get(
                "ga:{$viewId}",
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
                $metrics,
                $others
            );
        });
    }
//    public static function createForConfig(array $analyticsConfig): AnalyticsClient
//    {
//        $authenticatedClient = self::createAuthenticatedGoogleClient($analyticsConfig);
//
//        $googleService = new Google_Service_Analytics($authenticatedClient);
//
//        return self::createAnalyticsClient($analyticsConfig, $googleService);
//    }
//
//    public static function createAuthenticatedGoogleClient(array $config): Google_Client
//    {
//        $client = new Google_Client();
//
//        $client->setClassConfig(
//            'Google_Cache_File',
//            'directory',
//            $config['cache_location'] ?? storage_path('app/laravel-google-analytics/google-cache/')
//        );
//
//        $credentials = $client->loadServiceAccountJson(
//            $config['service_account_credentials_json'],
//            'https://www.googleapis.com/auth/analytics.readonly'
//        );
//
//        $client->setAssertionCredentials($credentials);
//
//        return $client;
//    }

}
?>