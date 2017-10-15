<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Excel;
use App\Models\Enterprise\Business;
use App\Models\Enterprise\GLN;
use Carbon\Carbon;
use App\Models\Icheck\Product\Vendor;
use App\Models\Icheck\Product\ProductAttr;
use App\Models\Icheck\Product\Hook;
use App\Models\Icheck\Product\HookProduct;
use Illuminate\Support\Facades\Log;
use App\Models\Enterprise\ProductDistributor;
use App\Models\Icheck\Product\Product;
class RobotAmazon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:amazon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Amazon Robot from excel';

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
        $path = storage_path('amazon');

        $files = scandir($path, 1);
        foreach ($files as $file) {
            $this->importFile($path.'/'.$file);
        }
    }



    protected function importFile($file)
    {
        $index = 0;
        Excel::load($file, function($reader)  use($index) {

            // Getting all results
            $results = $reader->get();
            foreach ($results as $row) {
                if($row['asin'] != 'N/A') {
                    var_dump($row);
                    $index++;
                    if($index == 2) dd(1);
                }else continue;

                
            }

        });
    }
}
