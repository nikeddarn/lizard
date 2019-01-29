<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Support\Breadcrumbs\FilteredCategoryBreadcrumbs;
use App\Support\Seo\Canonical\CanonicalLinkGenerator;
use App\Support\Seo\MetaTags\FilterCategoryMetaTags;
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
     * @var FilterCategoryMetaTags
     */
    private $filterCategoryMetaTags;

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
     * @param FilterCategoryMetaTags $filterCategoryMetaTags
     */
    public function __construct(Category $category, AttributeValue $attributeValue, SortProductsUrlGenerator $sortProductsUrlGenerator, FilteredCategoryProductsCreator $productsCreator, ShowProductsUrlGenerator $showProductsUrlGenerator, FilteredCategoryBreadcrumbs $breadcrumbs, MultiFiltersCreator $filtersCreator, PaginationLinksGenerator $paginationLinksGenerator, CanonicalLinkGenerator $canonicalLinkGenerator, FilterCategoryMetaTags $filterCategoryMetaTags)
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
        $this->filterCategoryMetaTags = $filterCategoryMetaTags;
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
        // retrieve selected filter attribute value
        $selectedAttributeValues = $this->getSelectedAttributesValues($filterItemUrl);

        $category = $this->getCategory($url, $selectedAttributeValues->first());

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

        // category content
        $categoryContent = $category->virtualCategories->count() ? $category->virtualCategories->first()->content : null;

        // seo pagination links
        $paginationLinks = $this->paginationLinksGenerator->createSeoLinks($products);

        if ($this->isPageIndexable($selectedAttributeValues->first())){
            // allow index this page for robots
            $noindexPage = false;
            // seo canonical url
            $metaCanonical = $this->canonicalLinkGenerator->createCanonicalLinkUrl();
        }else{
            // disallow index this page for robots
            $noindexPage = true;
            // without seo canonical
            $metaCanonical = null;
        }

        // category name
        $categoryName = $this->filterCategoryMetaTags->getCategoryName($category, $selectedAttributeValues);

        // title, description, keywords
        $pageTitle = $this->filterCategoryMetaTags->getCategoryTitle($category, $selectedAttributeValues);
        $pageDescription = $this->filterCategoryMetaTags->getCategoryDescription($category, $selectedAttributeValues);
        $pageKeywords = $this->filterCategoryMetaTags->getCategoryKeywords($category, $selectedAttributeValues);

        return view('content.shop.category.leaf_category.index')->with(compact('categoryContent', 'categoryName', 'breadcrumbs', 'products', 'filters', 'usedFilters', 'sortProductsUrls', 'sortProductsMethod', 'showProductsUrls', 'paginationLinks', 'metaCanonical', 'noindexPage', 'pageTitle', 'pageDescription', 'pageKeywords'));
    }

    /**
     * Get products for current page
     *
     * @param Category|Model $category
     * @param string $sortProductsMethod
     * @param Collection $selectedAttributeValues
     * @return LengthAwarePaginator
     */
    private function getProducts(Category $category, string $sortProductsMethod, Collection $selectedAttributeValues): LengthAwarePaginator
    {
        $products = $this->productsCreator->getFilteredProducts($category, $sortProductsMethod, $selectedAttributeValues);

        // redirect 404 for not existing pages
        if (request()->has('page') && request()->get('page') > $products->lastPage()) {
            abort(404);
        }

        return $products;
    }

    /**
     * Get attribute value of selected filter.
     *
     * @param string $filterItemUrl
     * @return Collection
     */
    private function getSelectedAttributesValues(string $filterItemUrl): Collection
    {
        $attributeValues = $this->attributeValue->newQuery()->where('url', $filterItemUrl)->with('attribute')->get();

        if ($attributeValues->count() !== 1) {
            abort(422);
        }

        return $attributeValues;
    }

    /**
     * Get category by url with relations.
     *
     * @param string $url
     * @param AttributeValue $attributeValue
     * @return Category|Model
     */
    private function getCategory(string $url, AttributeValue $attributeValue): Category
    {
        $category = $this->category->newQuery()
            ->where('url', $url)
            ->with(['products'])
            ->with(['virtualCategories' => function ($query) use ($attributeValue) {
                $query->where('attribute_values_id', $attributeValue->id);
            }])
            ->firstOrFail();

        if (!$category->isLeaf()) {
            abort(422);
        }

        return $category;
    }

    /**
     * Is page indexable for robots ?
     *
     * @param AttributeValue $attributeValue
     * @return bool
     */
    private function isPageIndexable(AttributeValue $attributeValue): bool
    {
        return (bool)$attributeValue->attribute->indexable;
    }
}
