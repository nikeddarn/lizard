<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Category\Real\UpdateVirtualCategoryRequest;
use App\Http\Requests\Admin\Category\Virtual\StoreVirtualCategoryRequest;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\VirtualCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class VirtualCategoryController extends Controller
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
     * @var VirtualCategory
     */
    private $virtualCategory;

    /**
     * CategoryController constructor.
     * @param Category $category
     * @param AttributeValue $attributeValue
     * @param VirtualCategory $virtualCategory
     */
    public function __construct(Category $category, AttributeValue $attributeValue, VirtualCategory $virtualCategory)
    {
        $this->category = $category;
        $this->attributeValue = $attributeValue;
        $this->virtualCategory = $virtualCategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

        $locale = app()->getLocale();

        $virtualCategories = $this->category->newQuery()->has('virtualCategories')->orderBy('name_' . $locale)->with('virtualCategories.attributeValue')->get();

        return view('content.admin.catalog.virtual_category.list.index')->with(compact('virtualCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $locale = app()->getLocale();

        $categories = $this->category->newQuery()->whereIsLeaf()->orderBy('name_' . $locale)->get();

        $attributeValues = $this->attributeValue->newQuery()->whereHas('attribute', function ($query) {
            $query->where('indexable', 1);
        })->orderBy('value_' . $locale)->get();

        return view('content.admin.catalog.virtual_category.create.index')->with(compact('categories', 'attributeValues'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVirtualCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVirtualCategoryRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $attributes = $request->only(['categories_id', 'attribute_values_id', 'name_ru', 'name_uk', 'title_ru', 'title_uk', 'description_ru', 'description_uk', 'keywords_ru', 'keywords_uk', 'content_ru', 'content_uk']);

        $this->virtualCategory->newQuery()->create($attributes);

        return redirect(route('admin.categories.virtual.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        if (Gate::denies('local-catalog-show', auth('web')->user())) {
            abort(401);
        }

        $virtualCategory = $this->virtualCategory->newQuery()->with('category', 'attributeValue')->findOrFail($id);

        return view('content.admin.catalog.virtual_category.show.index')->with(compact('virtualCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $virtualCategory = $this->virtualCategory->newQuery()->findOrFail($id);

        return view('content.admin.catalog.virtual_category.update.index')->with(compact('virtualCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVirtualCategoryRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateVirtualCategoryRequest $request, string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $virtualCategory = $this->virtualCategory->newQuery()->findOrFail($id);

        $attributes = $request->only(['name_ru', 'name_uk', 'title_ru', 'title_uk', 'description_ru', 'description_uk', 'keywords_ru', 'keywords_uk', 'content_ru', 'content_uk']);

        $virtualCategory->update($attributes);

        return redirect(route('admin.categories.virtual.show', ['id' => $virtualCategory->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        // retrieve category
        $virtualCategory = $this->category->newQuery()->findOrFail($id);

        $virtualCategory->delete();

        return redirect(route('admin.categories.virtual.index'));
    }
}
