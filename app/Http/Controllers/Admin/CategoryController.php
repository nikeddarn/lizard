<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Category\Real\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\Real\UpdateCategoryRequest;
use App\Models\Category;
use App\Support\ImageHandlers\CategoryImageHandler;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', $this->category);

        $categories = $this->category->defaultOrder()->withCount('products')->get()->toTree();

        return view('content.admin.catalog.category.list.index')->with(compact('categories'));
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

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        return view('content.admin.catalog.category.create.index')->with(compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @param CategoryImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCategoryRequest $request, CategoryImageHandler $imageHandler)
    {
        $this->authorize('create', $this->category);

        $attributes = $request->only(['name_ru', 'name_uk', 'url', 'parent_id', 'title_ru', 'title_uk', 'description_ru', 'description_uk', 'keywords_ru', 'keywords_uk', 'content_ru', 'content_uk']);

        $category = $this->category->create($attributes);

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

        // insert filters
        if ($request->has('filter_id')) {
            foreach (array_filter(array_unique($request->get('filter_id'))) as $filterId) {
                $category->filters()->attach($filterId);
            }
        }

        return redirect(route('admin.categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $id)
    {
        $this->authorize('view', $this->category);

        $category = $this->category->newQuery()->findOrFail($id);

        $products = $category->products()->with('primaryImage', 'filters', 'vendors')->paginate(config('admin.show_item_properties_per_page'));

        return view('content.admin.catalog.category.show.index')->with(compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(string $id)
    {
        $this->authorize('update', $this->category);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws Exception
     */
    public function update(UpdateCategoryRequest $request, string $id, CategoryImageHandler $imageHandler)
    {
        $this->authorize('update', $this->category);

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
     * @param CategoryImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws Exception
     */
    public function destroy(string $id, CategoryImageHandler $imageHandler)
    {
        $this->authorize('delete', $this->category);

        // retrieve category
        $category = $this->category->newQuery()->findOrFail($id);

        // update parent timestamp
        if ($category->parent_id){
            $category->parent()->touch();
        }

        // delete previous image
        $imageHandler->deleteCategoryIcon($category->id);

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
        if (!($request->ajax() && $request->hasFile('image'))) {
            return abort(405);
        }

        $this->validate($request, ['image' => 'image']);

        return '/storage/' . $request->image->store('images/categories/content', 'public');
    }
}
