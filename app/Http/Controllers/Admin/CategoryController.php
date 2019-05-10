<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Category\Real\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\Real\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Support\ImageHandlers\CategoryImageHandler;
use App\Support\Settings\SettingsRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Product
     */
    private $product;

    /**
     * CategoryController constructor.
     * @param Category $category
     * @param Product $product
     */
    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;
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

        $categories = $this->category->defaultOrder()->withCount('products')->get()->toTree();

        return view('content.admin.catalog.category.list.index')->with(compact('categories'));
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

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        return view('content.admin.catalog.category.create.index')->with(compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @param CategoryImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request, CategoryImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $attributes = $request->only(['name_ru', 'name_uk', 'url', 'parent_id', 'title_ru', 'title_uk', 'description_ru', 'description_uk', 'keywords_ru', 'keywords_uk', 'content_ru', 'content_uk']);

        $category = $this->category->newQuery()->create($attributes);

        // insert image
        if ($request->has('image')) {
            // uploaded image path
            $sourceImagePath = $request->image;
            // stored image path
            $destinationImagePath = 'images/categories/' . $category->id . '/' . uniqid() . '.jpg';
            // create image
            $imageHandler->createCategoryIcon($sourceImagePath, $destinationImagePath);
            // store image path
            $category->image = $destinationImagePath;
            $category->save();
        }

        return redirect(route('admin.categories.index'));
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

        $category = $this->category->newQuery()->findOrFail($id);

        $products = $category->products()->with('primaryImage', 'filters', 'vendors')->paginate(config('admin.show_item_properties_per_page'));

        return view('content.admin.catalog.category.show.index')->with(compact('category', 'products'));
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

        $category = $this->category->newQuery()->findOrFail($id);

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        return view('content.admin.catalog.category.update.index')->with(compact('category', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param string $id
     * @param CategoryImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function update(UpdateCategoryRequest $request, string $id, CategoryImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $category = $this->category->newQuery()->findOrFail($id);

        DB::beginTransaction();

        try {

            $categoryAttributes = $request->only(['name_ru', 'name_uk', 'url', 'title_ru', 'title_uk', 'description_ru', 'description_uk', 'keywords_ru', 'keywords_uk', 'content_ru', 'content_uk']);

            if ($request->has('image')) {
                // delete previous image
                $imageHandler->deleteCategoryIcon($category->id);

                // uploaded image path
                $sourceImagePath = $request->image;
                // stored image path
                $destinationImagePath = 'images/categories/' . $category->id . '/' . uniqid() . '.jpg';
                // create image
                $imageHandler->createCategoryIcon($sourceImagePath, $destinationImagePath);

                // store image path
                $categoryAttributes['image'] = $destinationImagePath;
            }

            $category->update($categoryAttributes);

            // change parent
            $parentId = (int)$request->get('parent_id');
            if ($category->parent_id != $parentId) {
                if ($parentId === 0) {
                    $category->saveAsRoot();
                } else {
                    $parent = $this->category->newQuery()->where('id', $parentId)->first();
                    $category->appendToNode($parent)->save();
                }
            }

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return redirect(route('admin.categories.show', ['id' => $category->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @param CategoryImageHandler $categoryImageHandler
     * @param SettingsRepository $settingsRepository
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function destroy(string $id, CategoryImageHandler $categoryImageHandler, SettingsRepository $settingsRepository)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        // retrieve category
        $category = $this->category->newQuery()->findOrFail($id);

        // update parent timestamp
        if ($category->parent_id) {
            $category->parent()->touch();
        }

        // get settings
        $deleteProductSettings = $settingsRepository->getProperty('shop.delete_product');

        // delete products
        if ($deleteProductSettings['delete_product_on_delete_category']) {
            // get children and self categories ids
            $deletingCategoriesIds = $this->category->newQuery()->descendantsAndSelf($id)->pluck('id')->toArray();

            // get deleting products
            $categoriesProducts = $this->product->newQuery()
                ->whereHas('categories', function ($query) use ($deletingCategoriesIds) {
                    $query->whereIn('id', $deletingCategoriesIds);
                })
                ->doesntHave('stockStorages')
                ->with('vendorProducts', 'storages', 'categories')
                ->get();

            foreach ($categoriesProducts as $product) {
                // delete or archive product (via 'deleting' event listeners)
                $product->delete();
            }
        }

        // delete previous image
        $categoryImageHandler->deleteCategoryIcon($category->id);

        // delete category's branch
        $category->delete();

        return redirect(route('admin.categories.index'));
    }

    /**
     * Up the category in list
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws Exception
     */
    public function up(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $category = $this->category->newQuery()->findOrFail($id);

        try {
            DB::beginTransaction();

            $category->up();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
        return redirect(route('admin.categories.index'));
    }

    /**
     * Down the category in list
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws Exception
     */
    public function down(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $category = $this->category->newQuery()->findOrFail($id);

        try {
            DB::beginTransaction();

            $category->down();

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return redirect(route('admin.categories.index'));
    }

    /**
     * Store image on public disk. Return image url.
     *
     * @param Request $request
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadImage(Request $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        if (!($request->ajax() && $request->hasFile('image'))) {
            return abort(405);
        }

        $this->validate($request, ['image' => 'image']);

        return '/storage/' . $request->image->store('images/categories/content', 'public');
    }

    /**
     * Has category products ?
     *
     * @param Request $request
     * @param string $id
     * @return string
     */
    public function isEmpty(Request $request, string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        if (!$request->ajax()) {
            abort(403);
        }

        $category = $this->category->newQuery()->withCount('products')->findOrFail($id);

        return ($category->isLeaf() && !$category->products_count) ? 'true' : 'false';
    }

    /**
     * Set product published.
     *
     * @return bool|RedirectResponse
     */
    public function publishCategory()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $categoryId = (int)request()->get('category_id');

        $descendantsCategories = $this->category->newQuery()->descendantsAndSelf($categoryId);
        $ancestorsCategories = $this->category->newQuery()->ancestorsAndSelf($categoryId);

        $categories = $descendantsCategories->merge($ancestorsCategories);

        foreach ($categories as $category) {
            $category->published = 1;
            $category->save();
        }

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Set product un published.
     *
     * @return bool|RedirectResponse
     */
    public function unPublishCategory()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $categoryId = (int)request()->get('category_id');

        $categories = $this->category->newQuery()->descendantsAndSelf($categoryId);

        foreach ($categories as $category) {
            $category->published = 0;
            $category->save();
        }

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }
}
