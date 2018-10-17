<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryFilter;
use App\Models\Filter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class CategoryFilterController extends Controller
{
    /**
     * @var CategoryFilter
     */
    private $categoryFilter;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Filter
     */
    private $filter;

    /**
     * CategoryFilterController constructor.
     * @param CategoryFilter $categoryFilter
     * @param Category $category
     * @param Filter $filter
     */
    public function __construct(CategoryFilter $categoryFilter, Category $category, Filter $filter)
    {
        $this->categoryFilter = $categoryFilter;
        $this->category = $category;
        $this->filter = $filter;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(string $id)
    {
        $this->authorize('create', $this->categoryFilter);

        $locale = app()->getLocale();

        $category = $this->category->newQuery()->findOrFail($id);

        $filters = $this->filter->newQuery()->orderBy("name_$locale")->get();

        return view('content.admin.catalog.category_filter.create.index')->with([
            'category' => $category,
            'filters' => $filters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->categoryFilter);

        $categoriesId = $request->get('categories_id');

        $this->validate($request, [
            'filters_id' => ['integer', Rule::unique('category_filter', 'filters_id')->where('categories_id', $categoriesId)],
            'categories_id' => ['integer'],
        ]);


        $this->categoryFilter->newQuery()->create($request->only(['categories_id', 'filters_id']));

        return redirect(route('admin.categories.show', ['id' => $categoriesId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $this->authorize('delete', $this->categoryFilter);

        $categoriesId = $request->get('categories_id');
        $filtersId = $request->get('filters_id');

        $this->categoryFilter->newQuery()->where(['categories_id' => $categoriesId, 'filters_id' => $filtersId])->delete();

        return redirect(route('admin.categories.show', ['id' => $categoriesId]));
    }
}
