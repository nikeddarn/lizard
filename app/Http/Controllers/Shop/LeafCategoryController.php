<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\ProductPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LeafCategoryController extends Controller
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
     * CategoryController constructor.
     * @param Category $category
     * @param ExchangeRates $exchangeRates
     * @param ProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     * @param Attribute $attribute
     */
    public function __construct(Category $category, ExchangeRates $exchangeRates, ProductPrice $productPrice, ProductAvailability $productAvailability, Attribute $attribute)
    {
        $this->category = $category;
        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
        $this->attribute = $attribute;
    }

    /**
     * Show products of given leaf category.
     *
     * @param string $url
     * @return View
     */
    public function index(string $url): View
    {
        $category = $this->category->newQuery()->where('url', $url)->firstOrFail();

        if (!$category->isLeaf()) {
            abort(422);
        }

        $breadcrumbs = $this->getBreadcrumbs($category);

        $products = $this->getProducts($category);

        $filters = $this->getFilters($products->pluck('id')->toArray(), $category->url);

        return view('content.shop.category.leaf_category.index')->with(compact(
            'category', 'breadcrumbs', 'products', 'filters'));
    }


    /**
     * Get products with its properties.
     *
     * @param Category|Model $category
     * @return LengthAwarePaginator
     */
    private function getProducts(Category $category): LengthAwarePaginator
    {
        $products = $category->products()
            ->with('primaryImage', 'productImages', 'actualBadges')
            ->paginate(config('shop.show_items_per_page'));

        $exchangeRate = $this->exchangeRates->getRate();

        foreach ($products as $product) {
            // product prices
            $productPrice = $this->productPrice->getPrice($product);
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
     * Get products filters.
     *
     * @param array $productsId
     * @param string $categoryUrl
     * @return Collection
     */
    private function getFilters(array $productsId, string $categoryUrl)
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
            ->get()
            ->each(function (Attribute $attribute) use ($categoryUrl) {
                foreach ($attribute->attributeValues as $attributeValue) {
                    $attributeValue->href = route('shop.category.filter.single', [
                        'url' => $categoryUrl,
                        'filters' => $attributeValue->url,
                    ]);
                }
            });
    }

    /**
     * Get breadcrumbs.
     *
     * @param Category|Model $category
     * @return array
     */
    private function getBreadcrumbs(Category $category): array
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

        return $breadcrumbs;
    }
}
