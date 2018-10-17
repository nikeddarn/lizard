<?php

namespace App\Http\Controllers\Shop;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function index(string $url)
    {
        $locale = app()->getLocale();
        $category = $this->category->newQuery()->where('url', $url)->firstOrFail();
        $breadcrumbs = $this->category->newQuery()->ancestorsAndSelf($category->id);

        $children = $category->children()->defaultOrder()->with(['categoryFilter.filter' => function ($query) use($locale){
            $query->groupBy("filters.name_$locale");
        }])->get();
//        var_dump($children->groupBy("filters.name_$locale")->toArray());exit;

        if ($category->isLeaf()) {

        } else {
            $children = $category->children()
                ->leftJoin('category_filter', 'categories.id', '=', 'category_filter.categories_id')
                ->leftJoin('filters', 'filters.id', '=', 'category_filter.filters_id')
                ->selectRaw("filters.name_$locale AS filter, CONCAT('[', GROUP_CONCAT(JSON_OBJECT('url', categories.url, 'name',  categories.name_$locale, 'image', categories.image) ORDER BY categories.name_$locale ASC SEPARATOR ','), ']') AS subcategories")
                ->groupBy('filters.id')
                ->orderByRaw('ISNULL(filter), filter ASC')
                ->get();

//            var_dump($children->first()->subcategories);exit;

            return view('content.shop.category.categories_list.index')->with([
                'category' => $category,
                'breadcrumbs' => $breadcrumbs,
                'children' => $children
            ]);
        }
    }
}
