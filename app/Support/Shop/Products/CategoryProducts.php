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

class CategoryProducts extends ProductProperties
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
        $query = $this->makeRetrieveProductsQuery($category);

        $query = $this->addRelations($query);

        $query = $this->sortProductsBy($query, $sortMethod);

        $products = $query->paginate(config('shop.show_items_per_page'))->appends(request()->query());

        $this->addProductsProperties($products);

        return $products;
    }

    /**
     * Make query.
     *
     * @param Category $category
     * @return Builder
     */
    protected function makeRetrieveProductsQuery(Category $category): Builder
    {
        return $category->products()->getQuery();
    }

    /**
     * Add relations to query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function addRelations(Builder $query): Builder
    {
        return $query->with('primaryImage', 'productImages', 'actualBadges', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city')
            ->with(['favouriteProducts' => function($query){
                if (auth('web')->check()){
                    $query->where('users_id', auth('web')->id());
                }elseif (request()->hasCookie('uuid')){
                    $query->where('users_id', request()->cookie('uuid'));
                }
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
        switch ($sortMethod){
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
