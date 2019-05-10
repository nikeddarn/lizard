<?php
/**
 * Category products creator.
 */

namespace App\Support\Shop\Products;


use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SearchProducts extends AbstractProduct
{
    /**
     * Get found products' ids
     *
     * @param string $searchingText
     * @return array
     */
    public function getFoundProductsIds(string $searchingText): array
    {
        return Product::search($searchingText)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Get products with its properties.
     *
     * @param array $productsIds
     * @return Collection
     */
    public function getProducts(array $productsIds): Collection
    {
        $user = $this->getUser();

        $query = $this->getRetrieveProductQuery()->whereIn('id', $productsIds);

        $query = $this->addRelations($query, $user);

        $products = $query->get();

        $this->addProductsProperties($products, $user);

        return $products;
    }

    /**
     * Add relations to query.
     *
     * @param Builder $query
     * @param $user
     * @return Builder
     */
    private function addRelations(Builder $query, $user): Builder
    {
        $query->with('primaryImage', 'productImages', 'productVideos', 'actualBadges', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city');

        if ($user) {
            $query->with(['favouriteProducts' => function ($query) use ($user) {
                $query->where('users_id', $user->id);
            }]);
        }

        return $query;
    }
}
