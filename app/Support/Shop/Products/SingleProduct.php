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
        $user = $this->getUser();

        $query = $this->makeRetrieveProductsQuery($url);

        $products = $this->addRelations($query, $user)->get();

        $this->addProductsProperties($products, $user);

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
     * @param $user
     * @return Builder
     */
    protected function addRelations(Builder $query, $user = null): Builder
    {
        $query->with('productImages', 'productVideos', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city', 'attributeValues.attribute');

        if ($user) {
            $query->with(['favouriteProducts' => function ($query) use ($user) {
                $query->where('users_id', $user->id);
            }]);
        }

        return $query;
    }
}
