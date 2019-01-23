<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\VirtualCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * CategoryController constructor.
     * @param Category $category
     * @param AttributeValue $attributeValue
     */
    public function __construct(Category $category, AttributeValue $attributeValue)
    {
        $this->category = $category;
        $this->attributeValue = $attributeValue;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', $this->category);

        $locale = app()->getLocale();

        $virtualCategories = $this->category->newQuery()->has('virtualCategories')->orderBy('name_' . $locale)->with('virtualCategories.attributeValue')->get();

        return view('content.admin.catalog.virtual_category.list.index')->with(compact('virtualCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', $this->category);

        $locale = app()->getLocale();

        $categories = $this->category->newQuery()->whereIsLeaf()->orderBy('name_' . $locale)->get();

        $attributeValues = $this->attributeValue->newQuery()->orderBy('value_' . $locale)->get();

        return view('content.admin.catalog.virtual_category.create.index')->with(compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VirtualCategory  $virtualCategory
     * @return \Illuminate\Http\Response
     */
    public function show(VirtualCategory $virtualCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VirtualCategory  $virtualCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(VirtualCategory $virtualCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VirtualCategory  $virtualCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VirtualCategory $virtualCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VirtualCategory  $virtualCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(VirtualCategory $virtualCategory)
    {
        //
    }
}
