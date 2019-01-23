<?php

namespace App\Providers;

use App\Http\Composers\CommonAdminDataComposer;
use App\Http\Composers\CommonDataComposer;
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
        View::composer([
            'layouts.common',
            'layouts.shop',
            'layouts.product',
            'layouts.user',
        ], CommonDataComposer::class);

        View::composer('layouts.admin', CommonAdminDataComposer::class);
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
