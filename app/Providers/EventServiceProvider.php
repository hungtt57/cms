<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\CollaboratorHasBeenAdded' => [
            'App\Listeners\EmailCollaboratorCredentials',
        ],
        'App\Events\BusinessHasBeenApproved' => [
            'App\Listeners\EmailBusinessCredentials',
        ],
        'App\Events\BusinessWasDisapproved' => [
            'App\Listeners\EmailBusinessDisapproved',
        ],
        'App\Events\ProductsFileUploaded' => [
            'App\Listeners\ImportProductsFile',
        ],
        'App\Events\BusinessProductsFileUploaded' => [
            'App\Listeners\BusinessImportProductsFile',
        ],
        'App\Events\ImportFileManagerUser' => [
            'App\Listeners\ListenImportFileManagerUser',
        ],
        'product.saved' => [
            'App\Listeners\ProductSaved'
        ],
        'App\Events\FileEditProductDistributor' => [
            'App\Listeners\FileEditProductDistributorListen',
        ],

    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
