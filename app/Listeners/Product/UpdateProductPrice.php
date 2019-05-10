<?php

namespace App\Listeners\Product;

use App\Contracts\Shop\ProductBadgesInterface;
use App\Models\Product;
use App\Support\ProductBadges\ProductBadges;
use App\Support\ProductPrices\VendorProductPrice;
use App\Support\Settings\SettingsRepository;

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
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * Create the event listener.
     *
     * @param VendorProductPrice $productPrice
     * @param ProductBadges $productBadges
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(VendorProductPrice $productPrice, ProductBadges $productBadges, SettingsRepository $settingsRepository)
    {
        $this->productPrice = $productPrice;
        $this->productBadges = $productBadges;
        $this->settingsRepository = $settingsRepository;
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

        $productPriceConditions = $this->settingsRepository->getProperty('vendor.product_price_conditions');

        // allow update product
        if ($productPriceConditions['update_own_product_price_on_vendor_sync'] || !$this->isProductOwn($product)) {
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
        return (bool)$product->stockStorages->count();
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
