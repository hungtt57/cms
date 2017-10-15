<?php

namespace App\Remote;

use Illuminate\Support\ServiceProvider;

class RemoteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Remote::class, function ($app) {
            return new Remote(config('remote'));
        });
    }
}
