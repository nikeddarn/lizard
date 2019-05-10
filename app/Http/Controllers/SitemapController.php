<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\StaticPage;
use App\Models\VirtualCategory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use stdClass;

class SitemapController extends Controller
{
    /**
     * @var string
     */
    const LASTMOD_FORMAT = 'Y-m-d\TH:i:sP';
    /**
     * @var string
     */
    const TIMEZONE = 'Europe/Kiev';
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var AttributeValue
     */
    private $attributeValue;
    /**
     * @var StaticPage
     */
    private $staticPage;

    /**
     * SitemapController constructor.
     * @param Category $category
     * @param Product $product
     * @param AttributeValue $attributeValue
     * @param StaticPage $staticPage
     */
    public function __construct(Category $category, Product $product, AttributeValue $attributeValue, StaticPage $staticPage)
    {
        $this->category = $category;
        $this->product = $product;
        $this->attributeValue = $attributeValue;
        $this->staticPage = $staticPage;
    }

    /**
     * Create sitemap.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        $siteMapItems = $this->getStaticPagesItems();

        // categories sitemap items
        $siteMapItems = $siteMapItems->merge($this->getCategoriesItems());

        // products sitemap items
        $siteMapItems = $siteMapItems->merge($this->getProductsItems());

        return response(view('common.sitemap.index')->with(compact('siteMapItems')))->header('Content-Type', 'text/xml');
    }

    /**
     * Get static pages sitemap items.
     *
     * @return Collection
     */
    private function getStaticPagesItems(): Collection
    {
        $siteMapItems = collect();

        $staticPages = $this->staticPage->newQuery()->where('indexable', 1)->get(['route', 'updated_at']);

        // main page changefreq
        $changefreq = config('seo.changefreq.static');

        foreach ($staticPages as $page) {
            $siteMapItems->push($this->createStaticPageSitemapItem($page, $changefreq));
        }

        return $siteMapItems;
    }

    /**
     * Create static page sitemap item.
     *
     * @param StaticPage $page
     * @param string $changefreq
     * @return stdClass
     */
    private function createStaticPageSitemapItem(StaticPage $page, string $changefreq): stdClass
    {
        $item = new stdClass();

        $item->url = route($page->route);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $page->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }

    /**
     * Get categories sitemap items.
     *
     * @return Collection
     */
    private function getCategoriesItems(): Collection
    {
        $siteMapItems = collect();

        $categories = $this->category->newQuery()
            ->where('published', 1)
            ->with(['virtualCategories' => function ($query) {
                $query->select(['updated_at']);
                $query->with(['attributeValue' => function ($query) {
                    $query->select(['url', 'updated_at']);
                }]);
            }])
            ->get(['url', 'updated_at']);

        // leaf category changefreq
        $leafCategoryChangefreq = config('seo.changefreq.leaf_category');

        // category changefreq
        $categoryChangefreq = config('seo.changefreq.category');

        foreach ($categories as $category) {

            if ($category->isLeaf()) {
                // leaf category sitemap item
                $siteMapItems->push($this->createLeafCategorySitemapItem($category, $leafCategoryChangefreq));
            } else {
                // category sitemap item
                $siteMapItems->push($this->createCategorySitemapItem($category, $categoryChangefreq));
            }

            // get category's url
            $categoryUrl = $category->url;


            // virtual categories sitemap item
            foreach ($category->virtualCategories as $virtualCategory) {
                $siteMapItems->push($this->createVirtualCategorySitemapItem($categoryUrl, $virtualCategory, $leafCategoryChangefreq));
            }
        }

        return $siteMapItems;
    }

    /**
     * Create sitemap item of leaf category.
     *
     * @param Category $category
     * @param string $changefreq
     * @return stdClass
     */
    private function createCategorySitemapItem(Category $category, string $changefreq): stdClass
    {
        $item = new stdClass();

        $item->url = route('shop.category.index', ['url' => $category->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $category->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }

    /**
     * Create sitemap item of leaf category.
     *
     * @param Category $category
     * @param string $changefreq
     * @return stdClass
     */
    private function createLeafCategorySitemapItem(Category $category, string $changefreq): stdClass
    {

        $item = new stdClass();

        $item->url = route('shop.category.leaf.index', ['url' => $category->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $category->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }

    /**
     * Create sitemap item of virtual category.
     *
     * @param string $categoryUrl
     * @param VirtualCategory $virtualCategory
     * @param string $changefreq
     * @return stdClass
     */
    private function createVirtualCategorySitemapItem(string $categoryUrl, VirtualCategory $virtualCategory, string $changefreq): stdClass
    {
        $item = new stdClass();

        $item->url = route('shop.category.filter.single', ['url' => $categoryUrl, 'filter' => $virtualCategory->attributeValue->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $virtualCategory->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }

    /**
     * Get products sitemap items.
     *
     * @return Collection
     */
    private function getProductsItems(): Collection
    {
        $siteMapItems = collect();

        $products = $this->product->newQuery()
            ->where([
                ['published', '=', 1],
                ['is_archive', '=', 0],
            ])
            ->get(['url', 'updated_at']);

        $changefreq = config('seo.changefreq.product');

        foreach ($products as $product) {
            $siteMapItems->push($this->createProductSitemapItem($product, $changefreq));
        }

        return $siteMapItems;
    }

    /**
     * Create sitemap item of product.
     *
     * @param Product $product
     * @param string $changefreq
     * @return stdClass
     */
    private function createProductSitemapItem(Product $product, string $changefreq): stdClass
    {
        $item = new stdClass();

        $item->url = route('shop.product.index', ['url' => $product->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $product->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }
}
