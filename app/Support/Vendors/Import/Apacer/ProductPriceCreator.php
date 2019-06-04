<?php


namespace App\Support\Vendors\Import\Apacer;


use App\Models\Product;
use stdClass;

class ProductPriceCreator
{
    /**
     * Set product and vendor product prices.
     *
     * @param Product $product
     * @param stdClass $productRawData
     */
    public function setProductPrices(Product $product, stdClass $productRawData)
    {
        $vendorProduct = $product->vendorProducts->first();

        $vendorProduct->price = $productRawData->price;

        $retailPrice = $this->getRetailPrice($productRawData);

        $vendorProduct->recommendable_price = $retailPrice;
        $vendorProduct->retail_price = $retailPrice;

        $vendorProduct->save();
    }

    /**
     * Get retail price.
     *
     * @param stdClass $productRawData
     * @return float
     */
    private function getRetailPrice(stdClass $productRawData)
    {
        return $productRawData->price * 1.5;
    }
}
