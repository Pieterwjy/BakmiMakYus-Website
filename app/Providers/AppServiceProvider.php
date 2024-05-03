<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\UrlGenerator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    // }
    public function boot()
    {
        if (env('ENFORCE_SSL', false)) {
            $this->app['url']->forceScheme('https');
        }
    }
}
