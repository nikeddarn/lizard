<?php
/**
 * Create single product data.
 */

namespace App\Support\Shop\Products;


use Illuminate\Database\Eloquent\Builder;

class SingleProduct extends AbstractProduct
{
    /**
     * Get product with relations.
     *
     * @param string $url
     * @return mixed
     */
    public function getProduct(string $url)
    {
        $query = $this->makeRetrieveProductsQuery($url);

        $query = $this->addRelations($query);

        $products = $query->get();

        $this->addProductsProperties($products);

        return $products->first();
    }

    /**
     * Make query.
     *
     * @param string $url
     * @return Builder
     */
    protected function makeRetrieveProductsQuery(string $url): Builder
    {
        return $this->getRetrieveProductQuery()->where('url', $url);
    }

    /**
     * Add relations to query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function addRelations(Builder $query): Builder
    {
        return $query->with('productImages', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city', 'attributeValues.attribute');
    }
}
