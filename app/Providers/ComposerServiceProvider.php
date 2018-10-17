<?php

namespace App\Providers;

use App\Http\Composers\CommonDataComposer;
use App\Http\Composers\CommonUserDataComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // add common data to all views
        View::composer('*', CommonDataComposer::class);

        // add common user data to all views
        View::composer('*', CommonUserDataComposer::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
