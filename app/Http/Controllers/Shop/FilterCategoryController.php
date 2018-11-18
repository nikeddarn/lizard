<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\ProductPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FilterCategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;
    /**
     * @var ProductPrice
     */
    private $productPrice;
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var AttributeValue
     */
    private $attributeValue;

    /**
     * FilterCategoryController constructor.
     * @param Category $category
     * @param ExchangeRates $exchangeRates
     * @param ProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     * @param Attribute $attribute
     * @param AttributeValue $attributeValue
     */
    public function __construct(Category $category, ExchangeRates $exchangeRates, ProductPrice $productPrice, ProductAvailability $productAvailability, Attribute $attribute, AttributeValue $attributeValue)
    {
        $this->category = $category;
        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
        $this->attribute = $attribute;
        $this->attributeValue = $attributeValue;
    }

    /**
     * Show products of given leaf category.
     *
     * @param string $url
     * @param string $urlFilters
     * @return View
     */
    public function index(string $url, string $urlFilters): View
    {
        $category = $this->category->newQuery()->where('url', $url)->firstOrFail();

        if (!$category->isLeaf()) {
            abort(422);
        }

        $selectedAttributeValues = $this->getSelectedAttributeValues($urlFilters);

        $unfilteredProducts = $this->getUnfilteredProducts($category);

        $products = $this->getFilteredProducts($category, $selectedAttributeValues);

        $breadcrumbs = $this->getBreadcrumbs($category, $selectedAttributeValues);

        $filters = $this->getFilters($unfilteredProducts->pluck('id')->toArray());


        $this->createFiltersRoutes($filters, $selectedAttributeValues, $category->url);

        $usedFilters = $this->createUsedFilters($category, $filters, $selectedAttributeValues);

        return view('content.shop.category.leaf_category.index')->with(compact(
            'category', 'breadcrumbs', 'products', 'filters', 'usedFilters'));
    }

    /**
     * Get collection of selected attributes.
     *
     * @param string $filters
     * @return Collection
     */
    private function getSelectedAttributeValues(string $filters): Collection
    {
        $selectedValuesUrls = explode(',', $filters);

        return $this->attributeValue->newQuery()->whereIn('url', $selectedValuesUrls)->get();
    }

    /**
     * @param Category|Model $category
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getUnfilteredProducts(Category $category): Collection
    {
        return $category->products()->get();
    }

    /**
     * Get products with its properties.
     *
     * @param Category|Model $category
     * @param Collection $selectedAttributeValues
     * @return LengthAwarePaginator
     */
    private function getFilteredProducts(Category $category, Collection $selectedAttributeValues): LengthAwarePaginator
    {
        $query = $category->products()->with('primaryImage', 'productImages', 'actualBadges');

        foreach ($selectedAttributeValues->groupBy('attributes_id') as $groupedAttributeValues) {
            $query->whereHas('productAttributes', function ($query) use ($groupedAttributeValues) {
                $query->whereIn('attribute_values_id', $groupedAttributeValues->pluck('id')->toArray());
            });
        }

        $products = $query->paginate(config('shop.show_items_per_page'));


        $exchangeRate = $this->exchangeRates->getRate();

        foreach ($products as $product) {
            // product prices
            $productPrice = $this->productPrice->getUsersProductPrice($product);
            $product->price = $productPrice ? number_format($productPrice, 2, '.', ',') : null;
            $product->localPrice = ($productPrice && $exchangeRate) ? number_format($productPrice * $exchangeRate, 0, '.', ',') : null;

            // product storages
            $availableProductStorages = $this->productAvailability->getHavingProductStorages($product);
            $product->productAvailableStorages = $availableProductStorages;
            // product arrival time
            if (!$availableProductStorages->count()) {
                $availableTime = $this->productAvailability->getProductAvailableTime($product);
                if ($availableTime) {
                    if ($availableTime > Carbon::today()->addDay()) {
                        $product->availableTime = $availableTime;
                    } else {
                        $product->availableTime = true;
                    }
                }
            }
        }

        return $products;
    }

    /**
     * Get breadcrumbs.
     *
     * @param Category|Model $category
     * @param Collection $selectedAttributeValues
     * @return array
     */
    private function getBreadcrumbs(Category $category, Collection $selectedAttributeValues): array
    {
        $breadcrumbs = $this->category->newQuery()->ancestorsAndSelf($category->id)
            ->each(function (Category $category) {
                if ($category->isLeaf()){
                    $category->href = route('shop.category.leaf.index', ['url' =>$category->url]);
                }else{
                    $category->href = route('shop.category.index', ['url' =>$category->url]);
                }
            })
            ->pluck('href', 'name')->toArray();

        if ($selectedAttributeValues->count() === 1) {
            $selectedAttributeValuesItem = $selectedAttributeValues->first();
            $breadcrumbs[$selectedAttributeValuesItem->value] = $selectedAttributeValuesItem->url;
        }

        return $breadcrumbs;
    }

    /**
     * Get products filters.
     *
     * @param array $productsId
     * @return Collection
     */
    private function getFilters(array $productsId)
    {
        return $this->attribute->newQuery()
            ->with(['attributeValues' => function ($query) use ($productsId) {
                $query->whereHas('productAttributes', function ($query) use ($productsId) {
                    $query->whereIn('products_id', $productsId);
                });
            }])
            ->whereHas('attributeValues', function ($query) use ($productsId) {
                $query->whereHas('productAttributes', function ($query) use ($productsId) {
                    $query->whereIn('products_id', $productsId);
                });
            }, '>', 1)
            ->get();
    }

    private function createFiltersRoutes(Collection $filters, Collection $selectedAttributeValues, string $categoryUrl)
    {
        $selectedAttributeValuesIds = $selectedAttributeValues->pluck('id')->toArray();

        foreach ($filters as $filter) {
            foreach ($filter->attributeValues as $attributeValue) {

                if (in_array($attributeValue->id, $selectedAttributeValuesIds)) {
                    $attributeValue->checked = true;
                    $newSelectedItems = (clone $selectedAttributeValues)->keyBy('id')->forget($attributeValue->id);
                } else {
                    $newSelectedItems = (clone $selectedAttributeValues)->push($attributeValue);
                }

                $attributeValue->href = $this->createRouteForFilterItem($newSelectedItems, $categoryUrl);
            }
        }
    }

    private function createRouteForFilterItem(Collection $newSelectedItems, string $categoryUrl)
    {
        $newSelectedItemsCount = $newSelectedItems->count();

        if ($newSelectedItemsCount === 0) {
            return route('shop.category.leaf.index', ['url' => $categoryUrl]);
        } elseif ($newSelectedItemsCount === 1) {
            return route('shop.category.filter.single', [
                'url' => $categoryUrl,
                'filters' => $newSelectedItems->first()->url,
            ]);
        } else {
            return route('shop.category.filter.multi', [
                'url' => $categoryUrl,
                'filters' => implode(',', $newSelectedItems->pluck('url')->toArray()),
            ]);
        }
    }

    private function createUsedFilters(Category $category, Collection $filters, $selectedAttributeValues): Collection
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
}
