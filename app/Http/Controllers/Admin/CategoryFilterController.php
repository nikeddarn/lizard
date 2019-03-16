<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryFilter;
use App\Models\Filter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
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
     */
    public function create(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

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
     */
    public function destroy(Request $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $categoriesId = $request->get('categories_id');
        $filtersId = $request->get('filters_id');

        $this->categoryFilter->newQuery()->where(['categories_id' => $categoriesId, 'filters_id' => $filtersId])->delete();

        return redirect(route('admin.categories.show', ['id' => $categoriesId]));
    }
}
