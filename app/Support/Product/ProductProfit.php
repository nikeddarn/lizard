<?php


namespace App\Support\Product;


use App\Models\Product;

class ProductProfit
{
    /**
     * @param Product $product
     * @return mixed|null
     */
    public function getProfit(Product $product)
    {
        if (!$product->price1){
            return null;
        }

        return $this->getVendorProductProfit($product);
    }

    /**
     * @param Product $product
     * @return mixed|null
     */
    private function getVendorProductProfit(Product $product)
    {
        $vendorProducts = $product->vendorProducts();

        if (!$vendorProducts->count()){
            return null;
        }

        $minVendorPrice = $vendorProducts->min('price');

        if (!$minVendorPrice){
            return null;
        }

        return $product->price1 - $minVendorPrice;
    }
}
