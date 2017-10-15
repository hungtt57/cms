<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Social\Vendor;
use App\Models\Social\MRealtime;
use Carbon\Carbon;

class WriteActionLogsToCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'write-action-logs:csv {ting?}';

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
        $mapGtin2Gln = [];
        $headers = explode(',', 'GTIN,ProductName,UserId,UserName,Age,Gender,ActionType,ActionValue,Time,Country,City,Latitude,Longitude');
        $n = $this->argument('ting', 1);

        do {
            $logs = MRealtime::where('write', '<>', $n)
                ->take(10)
                ->get();


            // $logs = MRealtime::where('createdAt', '<', Carbon::now()->subHours(6))
            //     ->where('write', '<>', 2)
            //     ->take(10)
            //     ->get();

            foreach ($logs as $log) {
                if (isset($log->gln)) {
                    $gln = $log->gln;
                } elseif (isset($mapGtin2Gln[$log->gtinCode])) {
                    $gln = $mapGtin2Gln[$log->gtinCode];
                } else {
                    $gln = Vendor::whereHas('products', function ($query) use ($log) {
                        $query->where('gtin_code', $log->gtinCode);
                    })->first();

                    if (is_null($gln)) {
                        continue;
                    }

                    $gln = $gln->gln_code;
                }

                $dayFilepath = config('analytic.data_dir') . '/' . $gln . '_actions_' . $log->createdAt->format('Y-m-d') . '.csv';
                $monthFilepath = config('analytic.data_dir') . '/' . $gln . '_actions_' . $log->createdAt->format('Y-m') . '.csv';

                $type = $log->actionType;

                if ($type == 'like'
                    and !($log->getAttribute('actionValue.like') == "true"
                        or $log->getAttribute('actionValue.like') == true
                        or $log->getAttribute('actionValue.liked') == 1
                    )
                ) {
                    $type = 'unlike';
                }

                switch ($type) {
                    case 'scan':
                        $value = $log->getAttribute('actionValue.time');

                        break;

                    case 'vote':
                        $value = $log->getAttribute('actionValue.rate');

                        break;

                    default:
                        $value = '';

                        break;
                }

                $this->info('[' . $log->createdAt->toIso8601String() . '] ' . $log->userName . ' ' . $type . ' ' . $log->productName);

                $data = [
                    $log->gtinCode,
                    $log->productName,
                    $log->userId,
                    $log->userName,
                    @$log->age,
                    @$log->gender,
                    $type,
                    $value,
                    $log->createdAt->toIso8601String(),
                    @$log->country,
                    @$log->city,
                    @$log->lat,
                    @$log->long,
                ];

                $hasHeader = file_exists($dayFilepath);
                $handle = fopen($dayFilepath, 'a');

                if (!$hasHeader) {
                    fputcsv($handle, $headers);
                }

                fputcsv($handle, $data);
                fclose($handle);

                $hasHeader = file_exists($monthFilepath);
                $handle = fopen($monthFilepath, 'a');

                if (!$hasHeader) {
                    fputcsv($handle, $headers);
                }

                fputcsv($handle, $data);
                fclose($handle);
            }

            MRealtime::whereIn('_id', $logs->lists('id')->toArray())->update(['write' => $n]);
        } while ($logs->count() > 0);
    }
}
