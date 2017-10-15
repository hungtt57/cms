<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
//use App\Models\Icheck\Product\Product;
use App\GALib\AnalyticsLib;
use DB;
use App\Models\Collaborator\SearchResult;
use App\Models\Icheck\Product\SearchNotFound;
use App\Models\Collaborator\ContributeProduct;
class RemoveSearchResultNotFound extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove search result';

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
        $date = Carbon::now()->subDays(100)->startOfDay();
        $threeDay =  Carbon::now()->subDays(3)->startOfDay();

        ContributeProduct::whereIn('status', [
            ContributeProduct::STATUS_AVAILABLE_CONTRIBUTE,
            ContributeProduct::STATUS_DISAPPROVED
        ])->where('receivedAt','<',$threeDay)
            ->unset('contributorId')
            ->unset('contributedAt')
            ->unset('receivedAt')
            ->unset('amount')
            ->unset('approvedAt')
            ->unset('approvedBy');
        SearchResult::where('results',[])->where('createdAt','<',$date)->delete();
        $this->line('Xoa thanh cong!');

    }
}
