<?php

namespace App\Http\Controllers\Pages;

use App\Contracts\Shop\CastProductMethodInterface;
use App\Contracts\Shop\ProductBadgesInterface;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Slider;
use App\Models\StaticPage;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\User\RetrieveUser;
use Illuminate\Support\Collection;

class MainPageController extends Controller
{
    use RetrieveUser;
    /**
     * @var string
     */
    const MAIN_PAGE_ROUTE_NAME = 'main';
    /**
     * @var string
     */
    const MAIN_PAGE_SLIDER_KEY = 'main_page_top_slider';
    /**
     * @var StaticPage
     */
    private $staticPage;
    /**
     * @var Slider
     */
    private $slider;
    /**
     * @var ProductGroup
     */
    private $productGroup;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;

    /**
     * MainPageController constructor.
     * @param StaticPage $staticPage
     * @param Slider $slider
     * @param ProductGroup $productGroup
     * @param Category $category
     * @param Product $product
     * @param ExchangeRates $exchangeRates
     */
    public function __construct(StaticPage $staticPage, Slider $slider, ProductGroup $productGroup, Category $category, Product $product, ExchangeRates $exchangeRates)
    {
        $this->staticPage = $staticPage;
        $this->slider = $slider;
        $this->productGroup = $productGroup;
        $this->category = $category;
        $this->product = $product;
        $this->exchangeRates = $exchangeRates;
    }


    /**
     * Show main page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $locale = app()->getLocale();

        $pageData = $this->staticPage->newQuery()->where('route', self::MAIN_PAGE_ROUTE_NAME)
            ->first();

        $mainSlider = $this->slider->newQuery()
            ->where('key', self::MAIN_PAGE_SLIDER_KEY)
            ->with(['slides' => function ($query) {
                $query->orderBy('position');
            }])
            ->first();

        $productGroups = $this->getProductGroups($locale);

        return view('content.pages.main.index')->with(compact('pageData', 'mainSlider', 'productGroups'));
    }

    /**
     * Get products groups.
     *
     * @param string $locale
     * @return Collection
     */
    private function getProductGroups(string $locale): Collection
    {
        $course = $this->exchangeRates->getRate();

        $user = $this->getUser();

        $userPriceGroup = $user ? $user->price_group : 1;

        $productGroups = $this->productGroup->newQuery()->orderBy('position')->with('castProductMethod')->get();

        foreach ($productGroups as $group) {
            // retrieve products for each group
            $group->products = $this->castGroupProducts($group);
        }

        // cast group with products count more then in condition
        $filteredGroups = $productGroups->filter(function (ProductGroup $productGroup) use ($userPriceGroup, $course, $locale) {
            if ($productGroup->products->count() > $productGroup->min_count_to_show) {
                $this->setProductProperties($productGroup->products, $userPriceGroup, $course, $locale);
                return true;
            } else {
                return false;
            }
        });

        return $filteredGroups;
    }

    /**
     * Cast group's products.
     *
     * @param ProductGroup $group
     * @return Collection
     */
    private function castGroupProducts(ProductGroup $group): Collection
    {
        $castCategoriesIds = $this->category->newQuery()->descendantsAndSelf($group->categories_id)->pluck('id')->toArray();

        $query = $this->product->newQuery()
            ->whereHas('categories', function ($query) use ($castCategoriesIds) {
                $query->whereIn('id', $castCategoriesIds);
            })
            ->has('primaryImage');

        switch ($group->castProductMethod->interface_id) {
            case CastProductMethodInterface::NEW:
                $query->orderByDesc('created_at');
                break;
            case CastProductMethodInterface::PRICE_DOWN:
                $query->whereHas('actualBadges', function ($query) {
                    $query->where('id', ProductBadgesInterface::PRICE_DOWN);
                });
                break;
            case CastProductMethodInterface::POPULAR:
                $query->withCount('recentProducts')->orderByDesc('recent_products_count');
                break;
            case CastProductMethodInterface::RANDOM:
                $query->inRandomOrder();
                break;
        }

        return $query->limit($group->max_count_to_show)->with('primaryImage')->get();
    }

    /**
     * Set product properties.
     *
     * @param Collection $products
     * @param int $userPriceGroup
     * @param float $course
     * @param string $locale
     */
    private function setProductProperties(Collection $products, int $userPriceGroup, float $course, string $locale)
    {
        foreach ($products as $product) {
            if ($course) {
                $product->localPrice = number_format($product->{'price' . $userPriceGroup} * $course);
            }

            $product->href = route('shop.product.index', ['url' => $product->url, 'locale' => $locale]);
        }
    }
}
