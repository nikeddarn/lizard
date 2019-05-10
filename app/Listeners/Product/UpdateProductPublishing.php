<?php

namespace App\Listeners\Product;

use App\Support\ProductPublishing\ProductPublishManager;

class UpdateProductPublishing
{
    /**
     * @var ProductPublishManager
     */
    private $productPublishManager;

    /**
     * UpdateProductPublishing constructor.
     * @param ProductPublishManager $productPublishManager
     */
    public function __construct(ProductPublishManager $productPublishManager)
    {
        $this->productPublishManager = $productPublishManager;
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     * @throws \Exception
     */
    public function handle($event)
    {
        // get product
        $product = $event->product;

        $this->productPublishManager->updateProductPublish($product);
    }
}
