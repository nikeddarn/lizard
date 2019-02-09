<?php

namespace App\Listeners\Shop;

class UpdateProductCategoryTimestamp
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        // get product
        $product = $event->product;

        foreach ($product->categories as $category) {
            // update product's category timestamp
            $category->touch();

            $productAttributesValuesIds = $product->attributeValues->pluck('id')->toArray();

            $productVirtualCategories = $category->virtualCategories->whereIn('attribute_values_id', $productAttributesValuesIds)->all();

            foreach ($productVirtualCategories as $virtualCategory) {
                // update product's virtual category timestamp
                $virtualCategory->touch();
            }
        }
    }
}
