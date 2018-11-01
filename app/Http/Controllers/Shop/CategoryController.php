<?php

namespace App\Http\Controllers\Shop;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class CategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;

    /**
     * CategoryController constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
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
            abort(422);
        }

        $breadcrumbs = $this->getBreadcrumbs($category);

        $groupedChildren = $category->children()->with('filters')->get()->sortByDesc(function ($category) {
            if ($category->filters->count()) {
                return $category->filters->first()->name;
            } else {
                return null;
            }
        })->groupBy(function ($category) {
            if ($category->filters->count()) {
                return $category->filters->first()->name;
            } else {
                return null;
            }
        });

        return view('content.shop.category.categories_list.index')->with(compact('category', 'breadcrumbs', 'groupedChildren'));
    }

    /**
     * Get breadcrumbs.
     *
     * @param Category|Model $category
     * @return array
     */
    private function getBreadcrumbs(Category $category): array
    {
        $breadcrumbs = $this->category->newQuery()->ancestorsAndSelf($category->id)
            ->each(function (Category $category) {
                if ($category->isLeaf()){
                    $category->href = route('shop.category.leaf.index', ['url' =>$category->url]);
                }else{
                    $category->href = route('shop.category.index', ['url' =>$category->url]);
                }
            })
            ->pluck('href', 'name')->toArray();

        return $breadcrumbs;
    }
}
