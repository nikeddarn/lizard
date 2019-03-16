<?php
/**
 * Filtered category products creator.
 */

namespace App\Support\Shop\Products;


use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FilteredCategoryProducts extends CategoryProducts
{
    /**
     * Get products with its properties.
     *
     * @param Category|Model $category
     * @param string $sortMethod
     * @param Collection $selectedAttributeValues
     * @return LengthAwarePaginator
     */
    public function getFilteredProducts(Category $category, string $sortMethod, Collection $selectedAttributeValues): LengthAwarePaginator
    {
        $user = $this->getUser();

        $query = $this->getRetrieveCategoryProductsQuery($category);

        $query = $this->constraintQueryWithFilters($query, $selectedAttributeValues);

        $query = $this->addRelations($query);

        $query = $this->sortProductsBy($query, $sortMethod);

        $products = $query->paginate($this->productsPerPage)->appends(request()->query());

        $this->addProductsProperties($products, $user);

        return $products;
    }

    /**
     * Constraint query with selected filters.
     *
     * @param Builder $query
     * @param Collection $selectedAttributeValues
     * @return Builder
     */
    private function constraintQueryWithFilters(Builder $query, Collection $selectedAttributeValues): Builder
    {
        foreach ($selectedAttributeValues->groupBy('attributes_id') as $groupedAttributeValues) {
            $query->whereHas('productAttributes', function ($query) use ($groupedAttributeValues) {
                $query->whereIn('attribute_values_id', $groupedAttributeValues->pluck('id')->toArray());
            });
        }

        return $query;
    }
}
