<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Breadcrumbs\CategoryBreadcrumbs;
use App\Support\Seo\Canonical\CanonicalLinkGenerator;
use App\Support\Seo\Pagination\PaginationLinksGenerator;
use App\Support\Shop\Filters\FiltersCreator;
use App\Support\Shop\Products\CategoryProducts;
use App\Support\Url\UrlGenerators\ShowProductsUrlGenerator;
use App\Support\Url\UrlGenerators\SortProductsUrlGenerator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class LeafCategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var SortProductsUrlGenerator
     */
    private $sortProductsUrlGenerator;
    /**
     * @var CategoryProducts
     */
    private $productsCreator;
    /**
     * @var ShowProductsUrlGenerator
     */
    private $showProductsUrlGenerator;
    /**
     * @var CategoryBreadcrumbs
     */
    private $breadcrumbs;
    /**
     * @var FiltersCreator
     */
    private $filtersCreator;
    /**
     * @var PaginationLinksGenerator
     */
    private $paginationLinksGenerator;
    /**
     * @var CanonicalLinkGenerator
     */
    private $canonicalLinkGenerator;

    /**
     * CategoryController constructor.
     * @param Category $category
     * @param SortProductsUrlGenerator $sortProductsUrlGenerator
     * @param CategoryProducts $productsCreator
     * @param ShowProductsUrlGenerator $showProductsUrlGenerator
     * @param CategoryBreadcrumbs $breadcrumbs
     * @param FiltersCreator $filtersCreator
     * @param PaginationLinksGenerator $paginationLinksGenerator
     * @param CanonicalLinkGenerator $canonicalLinkGenerator
     */
    public function __construct(Category $category, SortProductsUrlGenerator $sortProductsUrlGenerator, CategoryProducts $productsCreator, ShowProductsUrlGenerator $showProductsUrlGenerator, CategoryBreadcrumbs $breadcrumbs, FiltersCreator $filtersCreator, PaginationLinksGenerator $paginationLinksGenerator, CanonicalLinkGenerator $canonicalLinkGenerator)
    {
        $this->category = $category;
        $this->sortProductsUrlGenerator = $sortProductsUrlGenerator;
        $this->productsCreator = $productsCreator;
        $this->showProductsUrlGenerator = $showProductsUrlGenerator;
        $this->breadcrumbs = $breadcrumbs;
        $this->filtersCreator = $filtersCreator;
        $this->paginationLinksGenerator = $paginationLinksGenerator;
        $this->canonicalLinkGenerator = $canonicalLinkGenerator;
    }

    /**
     * Show products of given leaf category.
     *
     * @param string $categoryUrl
     * @return View
     */
    public function index(string $categoryUrl): View
    {
        $category = $this->category->newQuery()->where('url', $categoryUrl)->with('products')->firstOrFail();

        if (!$category->isLeaf()) {
            abort(422);
        }

        // store category's id in session to create product details breadcrumbs
        session()->flash('product_category_id', $category->id);

        // define sort products method
        $sortProductsMethod = $this->sortProductsUrlGenerator->getCurrentQueryStringParameterValue();

        // create sort products urls
        $sortProductsUrls = $this->sortProductsUrlGenerator->createAvailableLinksData();

        // create show products urls
        $showProductsUrls = $this->showProductsUrlGenerator->createAvailableLinksData();

        // create breadcrumbs
        $breadcrumbs = $this->breadcrumbs->getCategoryBreadcrumbs($category->id);

        // get products for current page
        $products = $this->getProducts($category, $sortProductsMethod);

        // create filters
        $filters = $this->filtersCreator->getFilters($category, $categoryUrl);

        // category content
        $categoryContent = $category->content;

        // seo pagination links
        $paginationLinks = $this->paginationLinksGenerator->createSeoLinks($products);

        // seo canonical url
        $metaCanonical = $this->canonicalLinkGenerator->createCanonicalLinkUrl();

        return view('content.shop.category.leaf_category.index')->with(compact('categoryContent', 'breadcrumbs', 'products', 'filters', 'sortProductsUrls', 'sortProductsMethod', 'showProductsUrls', 'paginationLinks', 'metaCanonical'));
    }

    /**
     * Get products for current page
     *
     * @param Category|Model $category
     * @param string $sortProductsMethod
     * @return LengthAwarePaginator
     */
    private function getProducts(Category $category, string $sortProductsMethod):LengthAwarePaginator
    {
        $products = $this->productsCreator->getProducts($category, $sortProductsMethod);

        // redirect 404 for vot existing pages
        if (request()->has('page') && request()->get('page') > $products->lastPage()){
            abort(404);
        }

        return $products;
    }
}
