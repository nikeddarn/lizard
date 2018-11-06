<?php

namespace App\Http\Controllers\Shop;

use App\Models\Category;
use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductPrices\ProductPrice;
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
     * @var ProductPrice
     */
    private $productPrice;
    /**
     * @var ProductAvailability
     */
    private $productAvailability;

    /**
     * ProductDetailsController constructor.
     * @param Product $product
     * @param ExchangeRates $exchangeRates
     * @param ProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     */
    public function __construct(Product $product, ExchangeRates $exchangeRates, ProductPrice $productPrice, ProductAvailability $productAvailability)
    {
        $this->product = $product;
        $this->exchangeRates = $exchangeRates;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
    }

    public function index(string $url)
    {
        $product = $this->product->newQuery()->where('url', $url)->with('productImages', 'attributeValues.attribute', 'category')->firstOrFail();

        $this->addProductProperties($product);

        $comments = $product->productComments()->with('user')->paginate(config('shop.show_product_comments_per_page'));

        $breadcrumbs = $this->getBreadcrumbs($product);

        $this->addProductToRecentViewed($product);

        return view('content.shop/product.details.index')->with(compact('product', 'breadcrumbs', 'comments'));
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
        $productPrice = $this->productPrice->getPrice($product);
        $product->price = $productPrice ? number_format($productPrice, 2, '.', ',') : null;
        $product->localPrice = ($productPrice && $exchangeRate) ? number_format($productPrice * $exchangeRate, 0, '.', ',') : null;

        // product availability
        $availableProductStorages = $this->productAvailability->getHavingProductStorages($product);
        $product->productAvailableStorages = $availableProductStorages;
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
     * @param Product $product
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
     * @param Product $product
     * @return array
     */
    private function getBreadcrumbs(Product $product): array
    {
        $breadcrumbs = $product->category->newQuery()->ancestorsAndSelf($product->category->id)
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
}
