<?php
/**
 * Product filters creator.
 */

namespace App\Support\Shop\Filters;


use App\Contracts\Shop\UrlParametersInterface;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FiltersCreator
{
    /**
     * @var array
     */
    protected $filtersCount;
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * FiltersCreator constructor.
     * @param Attribute $attribute
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Attribute $attribute, SettingsRepository $settingsRepository)
    {
        $this->attribute = $attribute;

        $this->filtersCount = $settingsRepository->getProperty('shop.products_filters_show');
    }

    /**
     * Get products filters.
     *
     * @param Category|Model $category
     * @return Collection
     */
    public function getFilters(Category $category)
    {
        // get category products' ids
        $categoryProductsIds = $category->products->pluck('id')->toArray();

        $filters = $this->retrieveFilters($categoryProductsIds)
            ->sortByDesc(function (Attribute $attribute) use ($category) {
                // create attribute values urls
                $this->createSingleFilterItemsUrls($attribute, $category->url);

                // sort by opened desc
                return $attribute->defined_attribute_id;
            })
            ->sortByDesc(function (Attribute $attribute) use ($category) {
                // sort by opened desc
                return $attribute->showable;
            });

        // set is filter opened
        $openedFiltersCount = 0;

        foreach ($filters as $filter) {
            if ($openedFiltersCount < $this->filtersCount['min']) {
                if ($filter->attribute_values_count <= $this->filtersCount['max_values_count']) {
                    $filter->opened = true;
                    $openedFiltersCount++;
                } else {
                    $filter->opened = false;
                }
            } elseif ($openedFiltersCount >= $this->filtersCount['max']) {
                $filter->opened = false;
            } else {
                if ($filter->showable && $filter->attribute_values_count <= $this->filtersCount['max_values_count']) {
                    $filter->opened = true;
                    $openedFiltersCount++;
                } else {
                    $filter->opened = false;
                }
            }
        }

        return $filters;
    }

    /**
     * Retrieve all products filters that has more than one values.
     *
     * @param array $productsId
     * @return Collection
     */
    protected function retrieveFilters(array $productsId): Collection
    {
        return $this->attribute->newQuery()
            ->orderBy('defined_attribute_id')
            ->with(['attributeValues' => function ($query) use ($productsId) {
                $query->whereHas('productAttributes', function ($query) use ($productsId) {
                    $query->whereIn('products_id', $productsId);
                });
            }])
            ->withCount(['attributeValues' => function ($query) use ($productsId) {
                $query->whereHas('productAttributes', function ($query) use ($productsId) {
                    $query->whereIn('products_id', $productsId);
                });
            }])
            ->whereHas('attributeValues', function ($query) use ($productsId) {
                $query->whereHas('productAttributes', function ($query) use ($productsId) {
                    $query->whereIn('products_id', $productsId);
                });
            }, '>', 1)
            ->whereHas('productAttributes', function ($query) use ($productsId) {
                $query->whereIn('products_id', $productsId);
            })
            ->get();
    }

    /**
     * Create urls for each filter items.
     *
     * @param Attribute $attribute
     * @param string $categoryUrl
     */
    protected function createSingleFilterItemsUrls(Attribute $attribute, string $categoryUrl)
    {
        // value of current route's 'locale' parameter
        $localeParameterValue = request()->route()->parameter('locale');

        foreach ($attribute->attributeValues as $attributeValue) {
            $attributeValue->href = $this->createSingleFilterItemUrl($attributeValue, $categoryUrl, $localeParameterValue);
        }
    }

    /**
     * Create url for single filter's item.
     *
     * @param AttributeValue $attributeValue
     * @param string $categoryUrl
     * @param string|null $localeParameterValue
     * @return string
     */
    protected function createSingleFilterItemUrl(AttributeValue $attributeValue, string $categoryUrl, string $localeParameterValue = null): string
    {
        $routeParameters = [
            'url' => $categoryUrl,
            'filter' => $attributeValue->url,
            'locale' => $localeParameterValue,
        ];

        $currentQueryStringParameters = request()->query();

        unset($currentQueryStringParameters[UrlParametersInterface::FILTERED_PRODUCTS]);
        unset($currentQueryStringParameters[UrlParametersInterface::PRODUCTS_PAGE]);

        return route('shop.category.filter.single', $routeParameters) . $this->createQueryString($currentQueryStringParameters);
    }

    /**
     * Create query string.
     *
     * @param array $parameters
     * @return string
     */
    protected function createQueryString(array $parameters)
    {
        return $parameters ? '?' . urldecode(http_build_query($parameters)) : '';
    }
}
