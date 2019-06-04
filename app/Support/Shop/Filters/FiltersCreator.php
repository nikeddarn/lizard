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
     * @var AttributeValue
     */
    private $attributeValue;

    /**
     * FiltersCreator constructor.
     * @param Attribute $attribute
     * @param AttributeValue $attributeValue
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Attribute $attribute, AttributeValue $attributeValue, SettingsRepository $settingsRepository)
    {
        $this->attribute = $attribute;
        $this->attributeValue = $attributeValue;

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

        // set is filter opened
        $openedFiltersCount = 0;

        return $this->retrieveFilters($categoryProductsIds)
            ->each(function (Attribute $attribute) use ($categoryProductsIds) {
                $attribute->setRelation('attributeValues', $this->retrieveAttributeValues($attribute->id, $categoryProductsIds));
            })
            ->sortByDesc(function (Attribute $attribute) use ($category) {
                // create attribute values urls
                $this->createSingleFilterItemsUrls($attribute, $category->url);

                // sort by opened desc
                return $attribute->defined_attribute_id;
            })
            ->sortByDesc(function (Attribute $attribute) use ($category) {
                // sort by opened desc
                return $attribute->showable;
            })
            ->each(function (Attribute $attribute) use ($categoryProductsIds, &$openedFiltersCount) {
                $attributeValuesCount = $attribute->attributeValues->count();

                if ($openedFiltersCount < $this->filtersCount['min']) {
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
     * Retrieve all products filters.
     *
     * @param array $productsId
     * @return Collection
     */
    protected function retrieveFilters(array $productsId): Collection
    {
        return $this->attribute->newQuery()
            ->whereHas('attributeValues', function ($query) use ($productsId) {
                $query->whereHas('productAttributes', function ($query) use ($productsId) {
                    $query->whereIn('products_id', $productsId);
                });
            }, '>', 1)
            ->whereHas('productAttributes', function ($query) use ($productsId) {
                $query->whereIn('products_id', $productsId);
            })
            ->orderBy('defined_attribute_id')
            ->get();
    }

    /**
     * Retrieve attribute values.
     *
     * @param int $attributeId
     * @param array $productsId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function retrieveAttributeValues(int $attributeId, array $productsId)
    {
        return $this->attributeValue->newQuery()
            ->where('attributes_id', $attributeId)
            ->whereHas('productAttributes', function ($query) use ($attributeId, $productsId) {
                $query->where('attributes_id', $attributeId);
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
            'attribute' => $attributeValue->attributes_id,
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
