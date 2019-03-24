<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
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
     * SitemapController constructor.
     * @param Category $category
     * @param Product $product
     * @param AttributeValue $attributeValue
     */
    public function __construct(Category $category, Product $product, AttributeValue $attributeValue)
    {
        $this->category = $category;
        $this->product = $product;
        $this->attributeValue = $attributeValue;
    }

    public function index()
    {
        $siteMapItems = collect();

        // categories sitemap items
        $siteMapItems = $siteMapItems->merge($this->getCategoriesItems());
        // products sitemap items
        $siteMapItems = $siteMapItems->merge($this->getProductsItems());

        return response(view('common.sitemap.index')->with(compact('siteMapItems')))->header('Content-Type', 'text/xml');
    }

    /**
     * Get categories sitemap items.
     *
     * @return Collection
     */
    private function getCategoriesItems(): Collection
    {
        $siteMapItems = collect();

        $categories = $this->category->newQuery()->with('virtualCategories.attributeValue')->get();

        foreach ($categories as $category) {

            if ($category->isLeaf()) {
                // leaf category sitemap item
                $siteMapItems->push($this->createLeafCategorySitemapItem($category));
            } else {
                // category sitemap item
                $siteMapItems->push($this->createCategorySitemapItem($category));
            }

            // get category's url
            $categoryUrl = $category->url;

            // virtual categories sitemap item
            foreach ($category->virtualCategories as $virtualCategory) {
                $siteMapItems->push($this->createVirtualCategorySitemapItem($categoryUrl, $virtualCategory));
            }
        }

        return $siteMapItems;
    }

    /**
     * Create sitemap item of leaf category.
     *
     * @param $category
     * @return stdClass
     */
    private function createCategorySitemapItem(Category $category): stdClass
    {
        $changefreq = config('seo.changefreq.category');

        $item = new stdClass();

        $item->url = route('shop.category.index', ['url' => $category->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $category->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }

    /**
     * Create sitemap item of leaf category.
     *
     * @param $category
     * @return stdClass
     */
    private function createLeafCategorySitemapItem(Category $category): stdClass
    {
        $changefreq = config('seo.changefreq.leaf_category');

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
     * @return stdClass
     */
    private function createVirtualCategorySitemapItem(string $categoryUrl, VirtualCategory $virtualCategory): stdClass
    {
        $changefreq = config('seo.changefreq.leaf_category');

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
            ->where('is_archive', 0)
            ->get();

        foreach ($products as $product) {
            $siteMapItems->push($this->createProductSitemapItem($product));
        }

        return $siteMapItems;
    }

    /**
     * Create sitemap item of product.
     *
     * @param Product $product
     * @return stdClass
     */
    private function createProductSitemapItem(Product $product): stdClass
    {
        $changefreq = config('seo.changefreq.product');

        $item = new stdClass();

        $item->url = route('shop.product.index', ['url' => $product->url]);
        $item->lastmod = Carbon::createFromFormat('Y-m-d H:i:s', $product->updated_at, self::TIMEZONE)->format(self::LASTMOD_FORMAT);
        $item->changefreq = $changefreq;

        return $item;
    }
}
