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
        $user = $this->getUser();

        $query = $this->getRetrieveCategoryProductsQuery($category);

        $query = $this->addRelations($query, $user);

        $query = $this->sortProductsBy($query, $sortMethod);

        $products = $query->paginate($this->productsPerPage)->appends(request()->query());

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
    protected function addRelations(Builder $query, $user = null): Builder
    {
        $query->with('primaryImage', 'productImages', 'actualBadges', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city');

        if ($user) {
            $query->with(['favouriteProducts' => function ($query) use ($user) {
                $query->where('users_id', $user->id);
            }]);
        }

        return $query;
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
            case SortProductsInterface::ALPHABETICAL:
                $locale = app()->getLocale();
                $query->orderBy('name_' . $locale);
                break;
        }

        return $query;
    }
}
