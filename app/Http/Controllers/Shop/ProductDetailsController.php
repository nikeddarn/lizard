<?php

namespace App\Http\Controllers\Shop;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\UserProductPrice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ProductDetailsController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;
    /**
     * @var UserProductPrice
     */
    private $productPrice;
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * ProductDetailsController constructor.
     * @param Product $product
     * @param ExchangeRates $exchangeRates
     * @param UserProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     * @param Category $category
     * @param Attribute $attribute
     */
    public function __construct(Product $product, ExchangeRates $exchangeRates, UserProductPrice $productPrice, ProductAvailability $productAvailability, Category $category, Attribute $attribute)
    {
        $this->product = $product;
        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
        $this->category = $category;
        $this->attribute = $attribute;
    }

    public function index(string $url)
    {
        // retrieve product
        $product = $this->product->newQuery()->where('url', $url)
            ->with('productImages', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city')
            ->firstOrFail();

        // get product's id
        $productId = $product->id;

        // retrieve product's attributes
        $productAttributes = $this->attribute->newQuery()
            ->whereHas('attributeValues.productAttributes', function ($query) use ($productId) {
                $query->where('products_id', $productId);
            })
            ->with(['attributeValues' => function ($query) use ($productId) {
                $query->whereHas('productAttributes', function ($query) use ($productId) {
                    $query->where('products_id', $productId);
                });
            }])
            ->get();

        $this->addProductProperties($product);

        $comments = $product->productComments()->with('user')->paginate(config('shop.show_product_comments_per_page'));

        $breadcrumbs = $this->getBreadcrumbs($product);

        $this->addProductToRecentViewed($product);

        return view('content.shop.product.details.index')->with(compact('product', 'breadcrumbs', 'comments', 'productAttributes'));
    }

    /**
     * Append product properties.
     *
     * @param Product|Model $product
     * @return void
     */
    private function addProductProperties(Product $product)
    {
        $exchangeRate = $this->exchangeRates->getRate();

        // product prices
        $productPrice = $this->productPrice->getUsersProductPrice($product);
        $product->price = $productPrice ? $this->formatPrice($productPrice) : null;
        $product->localPrice = ($productPrice && $exchangeRate) ? $this->formatPrice($productPrice * $exchangeRate, 0) : null;

        // product availability
        $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);
        $product->isAvailable = $this->productAvailability->isProductAvailable($product);
        $product->expectedAt = $productExpectedAt;
        $product->isExpectedToday = ($productExpectedAt && $productExpectedAt < Carbon::today()->addDay()) ? true : false;

        // defect rate
        if ($product->sold_quantity >= config('shop.min_quantity_to_show_rate.defect')) {
            $product->defectRate = $product->defect_rate;
        }

        // product rating
        if ($product->rating_quantity >= config('shop.min_quantity_to_show_rate.product')) {
            $product->productRate = $product->rating;
        }
    }

    /**
     * @param Product|Model $product
     * @return void
     */
    private function addProductToRecentViewed(Product $product)
    {
        if (auth('web')->check()) {
            $product->recentProducts()->firstOrNew([
                'users_id' => auth('web')->id(),
            ])->touch();
        } else {
            $uuid = Cookie::get('uuid', Str::uuid());

            $product->recentProducts()->firstOrNew([
                'uuid' => $uuid,
            ])->touch();

            Cookie::queue(Cookie::forever('uuid', $uuid));
        }
    }

    /**
     * Get breadcrumbs.
     *
     * @param Product|Model $product
     * @return array
     */
    private function getBreadcrumbs(Product $product): array
    {
        if (session()->has('product_category_id')) {
            $categoryId = session()->get('product_category_id');
        } else {
            $categoryId = $product->categories()->first()->id;
        }

        $breadcrumbs = $this->category->newQuery()->ancestorsAndSelf($categoryId)
            ->each(function (Category $category) {
                if ($category->isLeaf()) {
                    $category->href = route('shop.category.leaf.index', ['url' => $category->url]);
                } else {
                    $category->href = route('shop.category.index', ['url' => $category->url]);
                }
            })
            ->pluck('href', 'name')->toArray();

        return $breadcrumbs;
    }

    /**
     * Format product price.
     *
     * @param float $price
     * @param int $decimals
     * @return string
     */
    private function formatPrice(float $price, int $decimals = 2)
    {
        return number_format($price, $decimals, '.', ',');
    }
}
