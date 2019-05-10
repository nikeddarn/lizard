<?php

namespace App\Http\Controllers\Content;

use App\Http\Requests\Content\ProductGroup\DeleteProductGroupRequest;
use App\Http\Requests\Content\ProductGroup\InsertProductGroupRequest;
use App\Http\Requests\Content\ProductGroup\UpdateProductGroupRequest;
use App\Models\CastProductMethod;
use App\Models\Category;
use App\Models\ProductGroup;
use App\Http\Controllers\Controller;
use App\Models\StaticPage;

class ProductGroupController extends Controller
{
    /**
     * @var ProductGroup
     */
    private $productGroup;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var CastProductMethod
     */
    private $castProductMethod;
    /**
     * @var StaticPage
     */
    private $staticPage;

    /**
     * SeoSettingsController constructor.
     * @param ProductGroup $productGroup
     * @param Category $category
     * @param CastProductMethod $castProductMethod
     * @param StaticPage $staticPage
     */
    public function __construct(ProductGroup $productGroup, Category $category, CastProductMethod $castProductMethod, StaticPage $staticPage)
    {
        $this->productGroup = $productGroup;
        $this->category = $category;
        $this->castProductMethod = $castProductMethod;
        $this->staticPage = $staticPage;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        $castProductMethods = $this->castProductMethod->newQuery()->get();

        return view('content.admin.page_content.product_group.create.index')->with(compact('categories', 'castProductMethods'));
    }

    /**
     * Store slide.
     *
     * @param InsertProductGroupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsertProductGroupRequest $request)
    {
        $groupPosition = (int)$this->productGroup->newQuery()->max('position') + 1;

        $groupData = [
            'name_ru' => $request->get('name_ru'),
            'name_uk' => $request->get('name_uk'),
            'min_count_to_show' => $request->get('min_count'),
            'max_count_to_show' => $request->get('max_count'),
            'categories_id' => $request->get('categories_id'),
            'cast_product_methods_id' => $request->get('cast_product_method'),
            'position' => $groupPosition,
        ];

        $this->productGroup->newQuery()->create($groupData);

        $this->updateMainPageTimestamp();

        return redirect(route('admin.content.main.edit'));
    }

    /**
     * Edit common settings.
     *
     * @param string $groupId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $groupId)
    {
        $group = $this->productGroup->newQuery()->with('castProductMethod', 'category')->findOrFail($groupId);

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        $castProductMethods = $this->castProductMethod->newQuery()->get();

        return view('content.admin.page_content.product_group.edit.index')->with(compact('group', 'categories', 'castProductMethods'));
    }

    /**
     * Update common settings.
     *
     * @param UpdateProductGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductGroupRequest $request)
    {
        $groupId = $request->get('group_id');

        $group = $this->productGroup->newQuery()->findOrFail($groupId);

        $groupData = [
            'name_ru' => $request->get('name_ru'),
            'name_uk' => $request->get('name_uk'),
            'min_count_to_show' => $request->get('min_count'),
            'max_count_to_show' => $request->get('max_count'),
            'categories_id' => $request->get('categories_id'),
            'cast_product_methods_id' => $request->get('cast_product_method'),
        ];

        $group->update($groupData);

        $this->updateMainPageTimestamp();

        return redirect(route('admin.content.main.edit'));
    }

    /**
     * Delete slider image.
     *
     * @param DeleteProductGroupRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(DeleteProductGroupRequest $request)
    {
        $groupId = $request->get('group_id');

        $this->productGroup->newQuery()->where('id', $groupId)->delete();

        $this->updateMainPageTimestamp();

        return back();
    }

    /**
     * Update main page timestamp.
     */
    private function updateMainPageTimestamp()
    {
        $mainPage = $this->staticPage->newQuery()->where('route', 'main')->first();

        if ($mainPage) {
            $mainPage->touch();
        }
    }
}
