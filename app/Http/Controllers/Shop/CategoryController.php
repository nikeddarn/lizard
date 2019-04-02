<?php

namespace App\Http\Controllers\Shop;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Support\Breadcrumbs\CategoryBreadcrumbs;
use App\Support\Seo\MetaTags\CategoryMetaTags;

class CategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var CategoryBreadcrumbs
     */
    private $breadcrumbs;
    /**
     * @var CategoryMetaTags
     */
    private $categoryMetaTags;

    /**
     * CategoryController constructor.
     * @param Category $category
     * @param CategoryBreadcrumbs $breadcrumbs
     * @param CategoryMetaTags $categoryMetaTags
     */
    public function __construct(Category $category, CategoryBreadcrumbs $breadcrumbs, CategoryMetaTags $categoryMetaTags)
    {
        $this->category = $category;
        $this->breadcrumbs = $breadcrumbs;
        $this->categoryMetaTags = $categoryMetaTags;
    }

    /**
     * Show subcategories list grouped by categories filters.
     *
     * @param string $url
     * @return $this
     */
    public function index(string $url)
    {
        $category = $this->category->newQuery()->where('url', $url)->firstOrFail();

        if ($category->isLeaf()){
            abort(404);
        }

        // create breadcrumbs
        $breadcrumbs = $this->breadcrumbs->getCategoryBreadcrumbs($category->id);

        $children = $category->children()->get();

        // title, description, keywords
        $pageTitle = $this->categoryMetaTags->getCategoryTitle($category);
        $pageDescription = $this->categoryMetaTags->getCategoryDescription($category);
        $pageKeywords = $this->categoryMetaTags->getCategoryKeywords($category);

        return view('content.shop.category.categories_list.index')->with(compact('category', 'breadcrumbs', 'children', 'pageTitle', 'pageDescription', 'pageKeywords'));
    }
}
