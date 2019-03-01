<?php
/**
 * Category products creator.
 */

namespace App\Support\Shop\Products;


use App\Contracts\Shop\SortProductsInterface;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CategoryProducts extends AbstractProduct
{
    /**
     * Get products with its properties.
     *
     * @param Category|Model $category
     * @param string $sortMethod
     * @return LengthAwarePaginator
     */
    public function getProducts(Category $category, string $sortMethod): LengthAwarePaginator
    {
        $query = $this->getRetrieveCategoryProductsQuery($category);

        $query = $this->addRelations($query);

        $query = $this->sortProductsBy($query, $sortMethod);

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

    /**
     * Add order by constraints to query.
     *
     * @param Builder $query
     * @param string $sortMethod
     * @return Builder
     */
    protected function sortProductsBy(Builder $query, string $sortMethod): Builder
    {
        switch ($sortMethod) {
            case SortProductsInterface::POPULAR:
                $query->withCount('recentProducts')->orderByDesc('recent_products_count');
                break;
            case SortProductsInterface::LOW_TO_HIGH:
                $query->orderBy('price1');
                break;
            case SortProductsInterface::HIGH_TO_LOW:
                $query->orderByDesc('price1');
                break;
            case SortProductsInterface::RATING:
                $query->orderByDesc('rating');
                break;
        }

        return $query;
    }
}
