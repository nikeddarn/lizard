<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Support\Breadcrumbs\FilteredCategoryBreadcrumbs;
use App\Support\Seo\Canonical\CanonicalLinkGenerator;
use App\Support\Seo\Pagination\PaginationLinksGenerator;
use App\Support\Shop\Filters\MultiFiltersCreator;
use App\Support\Shop\Products\FilteredCategoryProductsCreator;
use App\Support\Url\UrlGenerators\ShowProductsUrlGenerator;
use App\Support\Url\UrlGenerators\SortProductsUrlGenerator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FilterCategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var AttributeValue
     */
    private $attributeValue;
    /**
     * @var FilteredCategoryProductsCreator
     */
    private $productsCreator;
    /**
     * @var SortProductsUrlGenerator
     */
    private $sortProductsUrlGenerator;
    /**
     * @var ShowProductsUrlGenerator
     */
    private $showProductsUrlGenerator;
    /**
     * @var FilteredCategoryBreadcrumbs
     */
    private $breadcrumbs;
    /**
     * @var MultiFiltersCreator
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
     * FilterCategoryController constructor.
     * @param Category $category
     * @param AttributeValue $attributeValue
     * @param SortProductsUrlGenerator $sortProductsUrlGenerator
     * @param FilteredCategoryProductsCreator $productsCreator
     * @param ShowProductsUrlGenerator $showProductsUrlGenerator
     * @param FilteredCategoryBreadcrumbs $breadcrumbs
     * @param MultiFiltersCreator $filtersCreator
     * @param PaginationLinksGenerator $paginationLinksGenerator
     * @param CanonicalLinkGenerator $canonicalLinkGenerator
     */
    public function __construct(Category $category, AttributeValue $attributeValue, SortProductsUrlGenerator $sortProductsUrlGenerator, FilteredCategoryProductsCreator $productsCreator, ShowProductsUrlGenerator $showProductsUrlGenerator, FilteredCategoryBreadcrumbs $breadcrumbs, MultiFiltersCreator $filtersCreator, PaginationLinksGenerator $paginationLinksGenerator, CanonicalLinkGenerator $canonicalLinkGenerator)
    {
        $this->category = $category;
        $this->attributeValue = $attributeValue;
        $this->productsCreator = $productsCreator;
        $this->sortProductsUrlGenerator = $sortProductsUrlGenerator;
        $this->showProductsUrlGenerator = $showProductsUrlGenerator;
        $this->breadcrumbs = $breadcrumbs;
        $this->filtersCreator = $filtersCreator;
        $this->paginationLinksGenerator = $paginationLinksGenerator;
        $this->canonicalLinkGenerator = $canonicalLinkGenerator;
    }

    /**
     * Show products of given leaf category.
     *
     * @param string $url
     * @param string $filterItemUrl
     * @return View
     */
    public function index(string $url, string $filterItemUrl): View
    {
        $category = $this->category->newQuery()->where('url', $url)->with('products')->firstOrFail();

        $selectedAttributeValues = $this->attributeValue->newQuery()->where('url', $filterItemUrl)->get();

        // category must be leaf and has one selected filter
        if (!($category->isLeaf() && $selectedAttributeValues->count() === 1)) {
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

        // get category's products
        $products = $this->getProducts($category, $sortProductsMethod, $selectedAttributeValues);

        // create breadcrumbs
        $breadcrumbs = $this->breadcrumbs->getFilteredCategoryBreadcrumbs($category->id, $selectedAttributeValues);

        // create filters
        $filters = $this->filtersCreator->getMultiFilters($category, $selectedAttributeValues);

        // get used filters
        $usedFilters = $this->filtersCreator->createUsedFilters($category, $filters, $selectedAttributeValues);

        // seo pagination links
        $paginationLinks = $this->paginationLinksGenerator->createSeoLinks($products);

        // seo canonical url
        $metaCanonical = $this->canonicalLinkGenerator->createCanonicalLinkUrl();

        return view('content.shop.category.leaf_category.index')->with(compact('breadcrumbs', 'products', 'filters', 'usedFilters', 'sortProductsUrls', 'sortProductsMethod', 'showProductsUrls', 'paginationLinks', 'metaCanonical'));
    }

    /**
     * Get products for current page
     *
     * @param Category|Model $category
     * @param string $sortProductsMethod
     * @param Collection $selectedAttributeValues
     * @return LengthAwarePaginator
     */
    private function getProducts(Category $category, string $sortProductsMethod, Collection $selectedAttributeValues):LengthAwarePaginator
    {
        $products = $this->productsCreator->getFilteredProducts($category, $sortProductsMethod, $selectedAttributeValues);

        // redirect 404 for vot existing pages
        if (request()->has('page') && request()->get('page') > $products->lastPage()){
            abort(404);
        }

        return $products;
    }
}
