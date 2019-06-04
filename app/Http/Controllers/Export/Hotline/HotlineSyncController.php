<?php

namespace App\Http\Controllers\Export\Hotline;

use App\Contracts\Dealer\DealerInterface;
use App\Http\Requests\Export\Hotline\HotlineLinkCategoryRequest;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\DealerCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HotlineSyncController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var DealerCategory
     */
    private $dealerCategory;

    /**
     * HotlineExportController constructor.
     * @param Category $category
     * @param DealerCategory $dealerCategory
     */
    public function __construct(Category $category, DealerCategory $dealerCategory)
    {
        $this->category = $category;
        $this->dealerCategory = $dealerCategory;
    }

    public function index(): View
    {
        $categories = $this->category->defaultOrder()->with('dealerCategories')->get()->toTree();

        return view('content.admin.export.hotline.sync.list.index')->with(compact('categories'));
    }

    /**
     * Select export able categories.
     *
     * @param int $categoryId
     * @return View
     */
    public function create(int $categoryId)
    {
        $category = $this->category->newQuery()->findOrFail($categoryId);

        $dealerCategories = DealerCategory::scoped([ 'dealers_id' => DealerInterface::HOTLINE ])->defaultOrder()->withDepth()->get()->toTree();

        return view('content.admin.export.hotline.sync.link.index')->with(compact('category','dealerCategories'));
    }

    /**
     * Store selected categories.
     *
     * @param HotlineLinkCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(HotlineLinkCategoryRequest $request)
    {
        $categoryId = $request->get('category_id');
        $dealerCategoryId = $request->get('dealer_category_id');

        $category = $this->category->newQuery()->findOrFail($categoryId);

        $category->dealerCategories()->syncWithoutDetaching([$dealerCategoryId]);

        return redirect(route('admin.export.hotline.sync.list'));
    }

    /**
     * Unlink category.
     *
     * @param HotlineLinkCategoryRequest $request
     * @return RedirectResponse
     */
    public function delete(HotlineLinkCategoryRequest $request)
    {
        $categoryId = $request->get('category_id');
        $dealerCategoryId = $request->get('dealer_category_id');

        $category = $this->category->newQuery()->findOrFail($categoryId);

        $category->dealerCategories()->detach($dealerCategoryId);

        return redirect(route('admin.export.hotline.sync.list'));
    }

    /**
     * Publish category to hotline.
     *
     * @param HotlineLinkCategoryRequest $request
     * @return RedirectResponse|string
     */
    public function publish(HotlineLinkCategoryRequest $request)
    {
        $categoryId = $request->get('category_id');
        $dealerCategoryId = $request->get('dealer_category_id');

        $category = $this->category->newQuery()->findOrFail($categoryId);

        $category->dealerCategories()->updateExistingPivot($dealerCategoryId, [
            'published' => 1,
        ]);

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Un publish category.
     *
     * @param HotlineLinkCategoryRequest $request
     * @return RedirectResponse|string
     */
    public function unPublish(HotlineLinkCategoryRequest $request)
    {
        $categoryId = $request->get('category_id');
        $dealerCategoryId = $request->get('dealer_category_id');

        $category = $this->category->newQuery()->findOrFail($categoryId);

        $category->dealerCategories()->updateExistingPivot($dealerCategoryId, [
            'published' => 0,
        ]);

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }
}
