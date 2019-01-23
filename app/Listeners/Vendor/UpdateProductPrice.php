<?php

namespace App\Listeners\Vendor;

use App\Contracts\Shop\ProductBadgesInterface;
use App\Models\Product;
use App\Support\ProductBadges\ProductBadges;
use App\Support\ProductPrices\VendorProductPrice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductPrice
{
    /**
     * @var VendorProductPrice
     */
    private $productPrice;
    /**
     * @var ProductBadges
     */
    private $productBadges;

    /**
     * Create the event listener.
     *
     * @param VendorProductPrice $productPrice
     * @param ProductBadges $productBadges
     */
    public function __construct(VendorProductPrice $productPrice, ProductBadges $productBadges)
    {
        $this->productPrice = $productPrice;
        $this->productBadges = $productBadges;
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

        // allow update product
        if (config('vendor.price.update_own_product_price_on_vendor_sync') || !$this->isProductOwn($product)) {
            // get recalculated prices
            $newProductPrices = $this->productPrice->getProductPrices($product);

            // attach 'price down' badge
            if ($this->isProductPriceDown($product, $newProductPrices)) {
                $this->productBadges->insertProductBadge($product, ProductBadgesInterface::PRICE_DOWN);
            }

            // update product prices
            $product->update($newProductPrices);
        }
    }

    /**
     * Define is product presents on any own storage.
     *
     * @param Product $product
     * @return bool
     */
    private function isProductOwn(Product $product): bool
    {
        return (bool)$product->storageProducts()->count();
    }

    /**
     * Is new product price less then old?
     *
     * @param Product $product
     * @param array $newProductPrices
     * @return bool
     */
    private function isProductPriceDown(Product $product, array $newProductPrices)
    {
        return isset($product->price1, $newProductPrices['price1']) && $newProductPrices['price1'] < $product->price1;
    }
}
