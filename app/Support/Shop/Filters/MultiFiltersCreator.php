<?php
/**
 * Product multi filters creator.
 */

namespace App\Support\Shop\Filters;


use App\Contracts\Shop\UrlParametersInterface;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MultiFiltersCreator extends FiltersCreator
{
    /**
     * Get products filters.
     *
     * @param Category|Model $category
     * @param Collection $selectedAttributeValues
     * @return Collection
     */
    public function getMultiFilters(Category $category, Collection $selectedAttributeValues)
    {
        // set is filter opened
        $openedFiltersCount = 0;

        // get category products' ids
        $categoryProductsIds = $category->products->pluck('id')->toArray();

        // selected attributes ids
        $selectedFiltersIds = $selectedAttributeValues->pluck('attributes_id')->toArray();

        // retrieve possible filters
        return $this->retrieveFilters($categoryProductsIds)
            ->each(function (Attribute $attribute) use ($categoryProductsIds) {
                $attribute->setRelation('attributeValues', $this->retrieveAttributeValues($attribute->id, $categoryProductsIds));
            })
            ->sortByDesc(function (Attribute $attribute) use ($category, $selectedAttributeValues) {
                // create attribute values urls
                $this->createMultiFilterItemsUrls($attribute, $category->url, $selectedAttributeValues);

                // sort by opened desc
                return $attribute->defined_attribute_id;
            })
            ->sortByDesc(function (Attribute $attribute) use ($category) {
                // sort by opened desc
                return $attribute->showable;
            })
            ->each(function (Attribute $attribute) use ($categoryProductsIds, $selectedFiltersIds, &$openedFiltersCount) {
                $attributeValuesCount = $attribute->attributeValues->count();

                if (in_array($attribute->id, $selectedFiltersIds)) {
                    $attribute->opened = true;
                    $openedFiltersCount++;
                } elseif ($openedFiltersCount < $this->filtersCount['min']) {
                    if ($attributeValuesCount <= $this->filtersCount['max_values_count']) {
                        $attribute->opened = true;
                        $openedFiltersCount++;
                    } else {
                        $attribute->opened = false;
                    }
                } elseif ($openedFiltersCount >= $this->filtersCount['max']) {
                    $attribute->opened = false;
                } else {
                    if ($attribute->showable && $attributeValuesCount <= $this->filtersCount['max_values_count']) {
                        $attribute->opened = true;
                        $openedFiltersCount++;
                    } else {
                        $attribute->opened = false;
                    }
                }
            });
    }

    /**
     * @param Category|Model $category
     * @param Collection $filters
     * @param $selectedAttributeValues
     * @return Collection
     */
    public function createUsedFilters(Category $category, Collection $filters, $selectedAttributeValues): Collection
    {
        $usedFilters = (clone $filters)->filter(function (Attribute $filter) {
            return $filter->attributeValues->where('checked', true)->count();
        });

        $usedFilters->each(function (Attribute $filter) use ($selectedAttributeValues, $category) {

            $newSelectedItems = (clone $selectedAttributeValues)->filter(function (AttributeValue $selectedItem) use ($filter) {
                return $selectedItem->attributes_id !== $filter->id;
            });

            $filter->href = $this->createRouteForFilterItem($newSelectedItems, $category->url);
        });

        return $usedFilters;
    }

    /**
     * @param Attribute $attribute
     * @param string $categoryUrl
     * @param Collection $selectedAttributeValues
     */
    private function createMultiFilterItemsUrls(Attribute $attribute, string $categoryUrl, Collection $selectedAttributeValues)
    {
        $selectedAttributeValuesIds = $selectedAttributeValues->pluck('id')->toArray();

        foreach ($attribute->attributeValues as $attributeValue) {

            if (in_array($attributeValue->id, $selectedAttributeValuesIds)) {
                $attributeValue->checked = true;
                $newSelectedItems = (clone $selectedAttributeValues)->keyBy('id')->forget($attributeValue->id);
            } else {
                $newSelectedItems = (clone $selectedAttributeValues)->push($attributeValue);
            }

            $attributeValue->href = $this->createRouteForFilterItem($newSelectedItems, $categoryUrl);
        }
    }

    /**
     * @param Collection $newSelectedItems
     * @param string $categoryUrl
     * @return string
     */
    private function createRouteForFilterItem(Collection $newSelectedItems, string $categoryUrl)
    {
        $newSelectedItemsCount = $newSelectedItems->count();

        // value of current route's 'locale' parameter
        $localeParameterValue = request()->route()->parameter('locale');

        if ($newSelectedItemsCount === 0) {
            return $this->createNotFilteredItemUrl($categoryUrl, $localeParameterValue);
        } elseif ($newSelectedItemsCount === 1) {
            return $this->createSingleFilterItemUrl($newSelectedItems->first(), $categoryUrl, $localeParameterValue);
        } else {
            return $this->createMultiFilterItemUrl($newSelectedItems, $categoryUrl, $localeParameterValue);
        }
    }

    /**
     * Create url for not filtered item.
     *
     * @param string $categoryUrl
     * @param string|null $localeParameterValue
     * @return string
     */
    private function createNotFilteredItemUrl(string $categoryUrl, string $localeParameterValue = null)
    {
        $routeParameters = [
            'url' => $categoryUrl,
            'locale' => $localeParameterValue,
        ];

        // get query params
        $currentQueryStringParameters = request()->query();

        // unset unnecessary params
        unset($currentQueryStringParameters[UrlParametersInterface::FILTERED_PRODUCTS]);
        unset($currentQueryStringParameters[UrlParametersInterface::PRODUCTS_PAGE]);

        return route('shop.category.leaf.index', $routeParameters) . $this->createQueryString($currentQueryStringParameters);
    }

    /**
     * Create url for multi filter's item.
     *
     * @param Collection $newSelectedAttributeValues
     * @param string $categoryUrl
     * @param string|null $localeParameterValue
     * @return string
     */
    private function createMultiFilterItemUrl(Collection $newSelectedAttributeValues, string $categoryUrl, string $localeParameterValue = null): string
    {
        // value of 'filter' query string parameter
        $filterQueryStringValue = implode('-', $newSelectedAttributeValues->pluck('id')->toArray());

        $routeParameters = [
            'url' => $categoryUrl,
            'locale' => $localeParameterValue,
        ];

        // get query params
        $currentQueryStringParameters = request()->query();

        // add filter param
        $currentQueryStringParameters[UrlParametersInterface::FILTERED_PRODUCTS] = $filterQueryStringValue;

        // unset unnecessary params
        unset($currentQueryStringParameters[UrlParametersInterface::PRODUCTS_PAGE]);

        return route('shop.category.filter.multi', $routeParameters) . $this->createQueryString($currentQueryStringParameters);
    }
}
