<?php
/**
 * Category products creator.
 */

namespace App\Support\Shop\Products;


use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

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
        return Product::search($searchingText)->get()->pluck('id')->toArray();
    }

    /**
     * Get products with its properties.
     *
     * @param array $productsIds
     * @return LengthAwarePaginator
     */
    public function getProducts(array $productsIds): LengthAwarePaginator
    {
        $query = $this->getRetrieveProductQuery()->whereIn('id', $productsIds);

        $query = $this->addRelations($query);

        $products = $query->paginate($this->productsPerPage)->appends(request()->query());

        $this->addProductsProperties($products);

        return $products;
    }

    /**
     * Add relations to query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function addRelations(Builder $query): Builder
    {
        $user = $this->getUser();

        $userId = $user ? $user->id : null;

        return $query->with('primaryImage', 'productImages', 'actualBadges', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city')
            ->with(['favouriteProducts' => function ($query) use ($userId) {
                $query->where('users_id', $userId);
            }]);
    }
}
