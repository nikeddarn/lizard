<?php

namespace App\Providers;

use App\Events\Order\OrderCanceled;
use App\Events\Order\OrderCanceledByManager;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderUpdated;
use App\Events\Order\OrderUpdatedByManager;
use App\Events\Shop\CategoryDeleted;
use App\Events\Shop\CategorySaved;
use App\Events\Shop\ProductCreated;
use App\Events\Shop\ProductDeleted;
use App\Events\Shop\ProductDeleting;
use App\Events\Shop\ProductSaved;
use App\Events\Shop\ProductUpdated;
use App\Events\Vendor\VendorProductInserted;
use App\Events\Vendor\VendorProductUpdated;
use App\Listeners\Orders\SendOrderCreatedNotifications;
use App\Listeners\Orders\SendOrderDeletedByManagerNotifications;
use App\Listeners\Orders\SendOrderDeletedNotifications;
use App\Listeners\Orders\SendOrderUpdatedByManagerNotifications;
use App\Listeners\Orders\SendOrderUpdatedNotifications;
use App\Listeners\Shop\ArchiveOrDeleteProduct;
use App\Listeners\Shop\UpdateParentCategoryTimestamp;
use App\Listeners\Product\UpdateProductAvailability;
use App\Listeners\Shop\UpdateProductCategoryTimestamp;
use App\Listeners\Product\UpdateProductPrice;
use App\Listeners\Product\UpdateProductPublishing;
use App\Listeners\Product\UpdateProductSearchIndex;
use App\Listeners\Product\UpdateProductWarranty;
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

        // auth
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // product created
        ProductCreated::class => [
            UpdateProductPublishing::class,
            UpdateProductSearchIndex::class,
        ],

        // product updated
        ProductUpdated::class => [
            UpdateProductPublishing::class,
            UpdateProductSearchIndex::class,
        ],

        // insert new vendor product
        VendorProductInserted::class => [
            UpdateProductPrice::class,
            UpdateProductAvailability::class,
            UpdateProductPublishing::class,
            UpdateProductSearchIndex::class,
            UpdateProductWarranty::class,
        ],

        // update vendor product
        VendorProductUpdated::class => [
            UpdateProductPrice::class,
            UpdateProductAvailability::class,
            UpdateProductPublishing::class,
            UpdateProductSearchIndex::class,
            UpdateProductWarranty::class,
        ],

        // category changed
        CategorySaved::class => [
            UpdateParentCategoryTimestamp::class,
        ],
        CategoryDeleted::class => [
            UpdateParentCategoryTimestamp::class,
        ],

        // delete or archive product
        ProductDeleting::class => [
            ArchiveOrDeleteProduct::class,
        ],

        // product changed
        ProductSaved::class => [
            UpdateProductCategoryTimestamp::class,
        ],
        ProductDeleted::class => [
            UpdateProductCategoryTimestamp::class,
        ],

        // orders
        OrderCreated::class => [
            SendOrderCreatedNotifications::class,
        ],
        OrderUpdated::class => [
            SendOrderUpdatedNotifications::class,
        ],
        OrderUpdatedByManager::class => [
            SendOrderUpdatedByManagerNotifications::class,
        ],
        OrderCanceled::class => [
            SendOrderDeletedNotifications::class,
        ],
        OrderCanceledByManager::class => [
            SendOrderDeletedByManagerNotifications::class,
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
