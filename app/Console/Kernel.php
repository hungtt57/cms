<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use PhpParser\Comment;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        Commands\WriteActionLogsToCSV::class,
        Commands\ReportHCM::class,
        Commands\UserFollow::class,
        Commands\ScanCategory::class,
        Commands\GetDataChartBusiness::class,
        Commands\CountProduct::class,
        Commands\GetVendorChart::class,
        Commands\GetInfoCategory::class,
        Commands\AutoSetRelateOldData::class,
        Commands\GetBusinessCategoryChart::class,
        Commands\GetBusinessAge::class,
        Commands\GetBusinessLocation::class,
        Commands\RemoveSearchResultNotFound::class,
        Commands\GetLowQualityProduct::class,
        Commands\CheckLogScanNotFound::class,
        Commands\ScanProductBing::class,
        Commands\NotificationBusinessExpire::class,
        Commands\UpdateAttrProduct::class,
        Commands\PublishNew::class,
        Commands\RemoveContributeProduct::class,
        Commands\RunNotificationUserBusiness::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('scan-report')
//                 ->everyMinute()->withoutOverlapping();

//     $schedule->command('business:category')
//         ->cron('* */6 * * *')->withoutOverlapping();
//         $schedule->command('get:data')
//                  ->cron('* */6 * * *')->withoutOverlapping();
//        $schedule->command('vendor:chart')
//            ->cron('* */6 * * *')->withoutOverlapping();
//        $schedule->command('category:info')
//            ->cron('* */6 * * *')->withoutOverlapping();
//        $schedule->command('business:age')
//            ->cron('* */6 * * *')->withoutOverlapping();


        $schedule->command('get:data')
            ->dailyAt('1:00')->withoutOverlapping()->appendOutputTo(storage_path('get-data.log'));
        $schedule->command('vendor:chart')
            ->dailyAt('1:00')->withoutOverlapping()->appendOutputTo(storage_path('vendor-chart.log'));
        $schedule->command('business:age')
            ->daily()->withoutOverlapping()->appendOutputTo(storage_path('bussiness-age.log'));
        $schedule->command('business:location')
            ->daily()->withoutOverlapping()->appendOutputTo(storage_path('bussiness-location.log'));
        $schedule->command('business:category')
            ->dailyAt('1:00')->withoutOverlapping()->appendOutputTo(storage_path('business-category.log'));
        $schedule->command('remove:search')->daily()->withoutOverlapping();
        $schedule->command('check:logscannotfound')->daily()->withoutOverlapping();
        $schedule->command('low:product')->daily()->withoutOverlapping();
//        $schedule->command('scan:bing')->daily()->withoutOverlapping();
        $schedule->command('business:expire')
            ->dailyAt('0:30')->withoutOverlapping();
//        $schedule->command('publish-new')
//            ->everyFiveMinutes()->withoutOverlapping();
//        $schedule->command('auto:set')
//            ->hourly()->withoutOverlapping();
//        $schedule->command('business:category')
//            ->dailyAt('1:00')->withoutOverlapping();
//        $schedule->command('business:age')
//            ->dailyAt('1:00')->withoutOverlapping();

//
//                 $schedule->command('get:data')
//                  ->everyMinute()->withoutOverlapping();
    }
}
