<?php

namespace App\Providers;

use App\Events\Vendor\VendorProductInserted;
use App\Events\Vendor\VendorProductUpdated;
use App\Listeners\Vendor\UpdateProductAvailability;
use App\Listeners\Vendor\UpdateProductPrice;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // insert new vendor product
        VendorProductInserted::class => [
            UpdateProductPrice::class,
            UpdateProductAvailability::class,
        ],
        // update vendor product
        VendorProductUpdated::class => [
            UpdateProductPrice::class,
            UpdateProductAvailability::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
