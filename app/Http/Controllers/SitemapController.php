<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use App\Models\StaticPage;
use App\Models\VirtualCategory;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
     * @return Response
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
            ->defaultOrder()
            ->where('published', 1)
            ->with(['virtualCategories' => function ($query) {
                $query->select(['updated_at']);
                $query->with(['attributeValue' => function ($query) {
                    $query->select(['url', 'updated_at']);
                }]);
            }])
            ->get(['id', 'parent_id', '_lft', '_rgt', 'published', 'url', 'updated_at']);

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

        // add cross attribute with categories items
        $siteMapItems = $siteMapItems->merge($this->createAttributeCrossCategoriesItems($leafCategoryChangefreq));

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
     * Get virtual categories items.
     *
     * @param string $leafCategoryChangefreq
     * @return Collection
     */
    private function createAttributeCrossCategoriesItems(string $leafCategoryChangefreq)
    {
        $siteMapItems = collect();

        $attributesCountQuery = DB::table('attributes')
            ->join('attribute_values', 'attribute_values.attributes_id', '=', 'attributes.id')
            ->join('product_attribute', function ($join) {
                $join->on('product_attribute.attribute_values_id', '=', 'attribute_values.id')
                    ->whereRaw('product_attribute.attributes_id = attributes.id');
            })
            ->join('products', function ($join) {
                $join->on('products.id', '=', 'product_attribute.products_id')
                    ->where('products.published', 1);
            })
            ->join('category_product', 'category_product.products_id', '=', 'products.id')
            ->join('categories', function ($join) {
                $join->on('categories.id', '=', 'category_product.categories_id')
                    ->where('categories.published', 1);
            })
            ->selectRaw('attributes.id as aggregate_attribute_id, categories.id as aggregate_category_id, count(distinct(attribute_values.id)) as attribute_values_count')
            ->groupBy('attributes.id', 'categories.id');

        $attributesValues = $this->attributeValue->newQuery()
            ->doesntHave('virtualCategories')
            ->join('attributes', function ($join) {
                $join->on('attributes.id', '=', 'attribute_values.attributes_id')
                    ->where('attributes.indexable', 1);
            })
            ->join('product_attribute', function ($join) {
                $join->on('product_attribute.attribute_values_id', '=', 'attribute_values.id')
                    ->whereRaw('product_attribute.attributes_id = attributes.id');
            })
            ->join('products', function ($join) {
                $join->on('products.id', '=', 'product_attribute.products_id')
                    ->where('products.published', 1);
            })
            ->join('category_product', 'category_product.products_id', '=', 'products.id')
            ->join('categories', function ($join) {
                $join->on('categories.id', '=', 'category_product.categories_id')
                    ->where('categories.published', 1);
            })
            ->selectRaw('attributes.id as attribute_id, attribute_values.id, attribute_values.url, attribute_values.attributes_id, categories.id as category_id, categories.url as category_url, categories.updated_at as category_updated_at')
            ->distinct()
            ->joinSub($attributesCountQuery, 'attribute_values_aggregates', function ($join) {
                $join->on('attributes.id', '=', 'attribute_values_aggregates.aggregate_attribute_id')
                    ->on('categories.id', '=', 'attribute_values_aggregates.aggregate_category_id');
            })
            ->whereRaw('attribute_values_aggregates.attribute_values_count > 1')
            ->get();

        foreach ($attributesValues as $attributesValue) {
            $siteMapItems->push($this->createAttributeCrossCategorySitemapItem($attributesValue, $leafCategoryChangefreq));
        }

        return $siteMapItems;
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

        $item->url = route('shop.category.filter.single', ['url' => $categoryUrl, 'attribute' => $virtualCategory->attributeValue->attributes_id, 'filter' => $virtualCategory->attributeValue->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $virtualCategory->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }

    /**
     * Create sitemap item of virtual category.
     *
     * @param AttributeValue $attributeValue
     * @param string $changefreq
     * @return stdClass
     */
    private function createAttributeCrossCategorySitemapItem(AttributeValue $attributeValue, string $changefreq): stdClass
    {
        $item = new stdClass();

        $item->url = route('shop.category.filter.single', ['url' => $attributeValue->category_url, 'attribute' => $attributeValue->attributes_id, 'filter' => $attributeValue->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $attributeValue->category_updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
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
