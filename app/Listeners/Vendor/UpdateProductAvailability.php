<?php

namespace App\Listeners\Vendor;

use App\Contracts\Shop\ProductBadgesInterface;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductBadges\ProductBadges;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductAvailability
{
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var ProductBadges
     */
    private $productBadges;

    /**
     * Create the event listener.
     *
     * @param ProductAvailability $productAvailability
     * @param ProductBadges $productBadges
     */
    public function __construct(ProductAvailability $productAvailability, ProductBadges $productBadges)
    {
        //
        $this->productAvailability = $productAvailability;
        $this->productBadges = $productBadges;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // get product
        $product = $event->product;

        // add or remove 'ending badge'
        if ($this->productAvailability->isProductEnding($product)) {
            $this->productBadges->insertProductBadge($product, ProductBadgesInterface::ENDING);
        } else {
            $this->productBadges->deleteProductBadge($product, ProductBadgesInterface::ENDING);
        }
    }
}
