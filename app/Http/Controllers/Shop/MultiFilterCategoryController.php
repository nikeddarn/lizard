<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\UrlParametersInterface;
use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Support\Breadcrumbs\FilteredCategoryBreadcrumbs;
use App\Support\Headers\CacheControlHeaders;
use App\Support\Seo\MetaTags\MultiFilterCategoryMetaTags;
use App\Support\Seo\Pagination\PaginationLinksGenerator;
use App\Support\Shop\Filters\MultiFiltersCreator;
use App\Support\Shop\Products\FilteredCategoryProducts;
use App\Support\Url\UrlGenerators\ShowProductsUrlGenerator;
use App\Support\Url\UrlGenerators\SortProductsUrlGenerator;
use App\Support\User\RetrieveUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class MultiFilterCategoryController extends Controller
{
    use RetrieveUser;
    use CacheControlHeaders;

    /**
     * @var Category
     */
    private $category;
    /**
     * @var AttributeValue
     */
    private $attributeValue;
    /**
     * @var FilteredCategoryProducts
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
     * @var MultiFilterCategoryMetaTags
     */
    private $multiFilterCategoryMetaTags;

    /**
     * FilterCategoryController constructor.
     * @param Category $category
     * @param AttributeValue $attributeValue
     * @param SortProductsUrlGenerator $sortProductsUrlGenerator
     * @param FilteredCategoryProducts $productsCreator
     * @param ShowProductsUrlGenerator $showProductsUrlGenerator
     * @param FilteredCategoryBreadcrumbs $breadcrumbs
     * @param MultiFiltersCreator $filtersCreator
     * @param PaginationLinksGenerator $paginationLinksGenerator
     * @param MultiFilterCategoryMetaTags $multiFilterCategoryMetaTags
     */
    public function __construct(Category $category, AttributeValue $attributeValue, SortProductsUrlGenerator $sortProductsUrlGenerator, FilteredCategoryProducts $productsCreator, ShowProductsUrlGenerator $showProductsUrlGenerator, FilteredCategoryBreadcrumbs $breadcrumbs, MultiFiltersCreator $filtersCreator, PaginationLinksGenerator $paginationLinksGenerator, MultiFilterCategoryMetaTags $multiFilterCategoryMetaTags)
    {
        $this->category = $category;
        $this->attributeValue = $attributeValue;
        $this->productsCreator = $productsCreator;
        $this->sortProductsUrlGenerator = $sortProductsUrlGenerator;
        $this->showProductsUrlGenerator = $showProductsUrlGenerator;
        $this->breadcrumbs = $breadcrumbs;
        $this->filtersCreator = $filtersCreator;
        $this->paginationLinksGenerator = $paginationLinksGenerator;
        $this->multiFilterCategoryMetaTags = $multiFilterCategoryMetaTags;
    }

    /**
     * Show products of given leaf category.
     *
     * @param string $url
     * @return Response
     */
    public function index(string $url)
    {
        $category = $this->getCategory($url);

        $user = $this->getUser();

        $response = response()->make();

        $pageLastModified = $category->updated_at;

        $this->checkAndSetLastModifiedHeader($user, $response, $pageLastModified);

        $selectedAttributeValues = $this->getSelectedAttributesValues();

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

        // disable index this page for robots
        $noindexPage = true;

        // category name
        $categoryName = $this->multiFilterCategoryMetaTags->getCategoryName($category, $selectedAttributeValues);

        // title, description, keywords
        $pageTitle = $this->multiFilterCategoryMetaTags->getCategoryTitle($category, $selectedAttributeValues);
        $pageDescription = $this->multiFilterCategoryMetaTags->getCategoryDescription($category, $selectedAttributeValues);
        $pageKeywords = $this->multiFilterCategoryMetaTags->getCategoryKeywords($category, $selectedAttributeValues);

        $response->setContent(view($this->getViewPath())->with(compact('breadcrumbs', 'products', 'filters', 'usedFilters', 'sortProductsUrls', 'sortProductsMethod', 'showProductsUrls', 'noindexPage', 'categoryName', 'pageTitle', 'pageDescription', 'pageKeywords')));

        $this->checkAndSetEtagHeader($user, $response);

        return $response;
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

        // redirect 404 for vot existing pages
        if (request()->has('page') && request()->get('page') > $products->lastPage()) {
            abort(404);
        }

        return $products;
    }

    /**
     * Get category by url with relations.
     *
     * @param string $url
     * @return Category|Model
     */
    private function getCategory(string $url): Category
    {
        $category = $this->category->newQuery()
            ->where('published', 1)
            ->where('url', $url)
            ->with(['products' => function ($query) {
                $query->where([
                    ['published', '=', 1],
                    ['is_archive', '=', 0],
                ])
                    ->select('id');
            }])
            ->firstOrFail();

        if (!$category->isLeaf()) {
            abort(404);
        }

        return $category;
    }

    /**
     * Get attribute value of selected filter.
     *
     * @return Collection
     */
    private function getSelectedAttributesValues(): Collection
    {
        $selectedValuesIds = explode('-', request()->get(UrlParametersInterface::FILTERED_PRODUCTS));

        $selectedAttributeValues = $this->attributeValue->newQuery()
            ->whereIn('id', $selectedValuesIds)
            ->get();

        if ($selectedAttributeValues->count() < 2) {
            abort(404);
        }

        return $selectedAttributeValues;
    }

    /**
     * Get view path.
     *
     * @return string
     */
    private function getViewPath(): string
    {
        $viewFolder = 'content.shop.category.leaf.';

        if (request()->has(UrlParametersInterface::SHOW_PRODUCTS)) {
            $viewSubfolder = request()->get(UrlParametersInterface::SHOW_PRODUCTS);
        } else {
            $viewSubfolder = config('shop.products_show.canonical_show_method');
        }

        return $viewFolder . $viewSubfolder . '.index';
    }
}
