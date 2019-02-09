<?php

namespace App\Providers;

use App\Events\Shop\CategoryDeleted;
use App\Events\Shop\CategorySaved;
use App\Events\Shop\ProductDeleted;
use App\Events\Shop\ProductSaved;
use App\Events\Vendor\VendorProductInserted;
use App\Events\Vendor\VendorProductUpdated;
use App\Listeners\Shop\UpdateParentCategoryTimestamp;
use App\Listeners\Vendor\UpdateProductAvailability;
use App\Listeners\Shop\UpdateProductCategoryTimestamp;
use App\Listeners\Vendor\UpdateProductPrice;
use App\Listeners\Vendor\UpdateProductPublishing;
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
            UpdateProductPublishing::class,
        ],

        // update vendor product
        VendorProductUpdated::class => [
            UpdateProductPrice::class,
            UpdateProductAvailability::class,
            UpdateProductPublishing::class,
        ],

        // category changed
        CategorySaved::class => [
            UpdateParentCategoryTimestamp::class,
        ],
        CategoryDeleted::class => [
            UpdateParentCategoryTimestamp::class,
        ],

        // product changed
        ProductSaved::class => [
            UpdateProductCategoryTimestamp::class,
        ],
        ProductDeleted::class => [
            UpdateProductCategoryTimestamp::class,
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
