<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Models\Category;
use App\Models\Filter;
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
     * @var Filter
     */
    private $filter;

    /**
     * CategoryController constructor.
     * @param Category $category
     * @param Filter $filter
     */
    public function __construct(Category $category, Filter $filter)
    {
        $this->category = $category;
        $this->filter = $filter;
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

        return view('content.admin.catalog.category.list.index')->with([
            'categories' => $this->category->defaultOrder()->withCount('products')->get()->toTree(),
        ]);
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

        $filters = $this->filter->newQuery()->orderBy("name_$locale")->get();

        return view('content.admin.catalog.category.create.index')->with([
            'categories' => $this->category->defaultOrder()->withDepth()->get()->toTree(),
            'filters' => $filters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', $this->category);

        $attributes = $request->only(['name_ru', 'name_ua', 'url', 'parent_id', 'title_ru', 'title_ua', 'description_ru', 'description_ua', 'keywords_ru', 'keywords_ua', 'content_ru', 'content_ua']);

        // insert image
        if ($request->has('image')) {
            $attributes['image'] = $request->image->store('images/categories/intro', 'public');
        }

        $category = $this->category->create($attributes);

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

        $products = $category->products()->with('primaryImage', 'filters', 'parent')->paginate(config('admin.show_items_per_page'));

        return view('content.admin.catalog.category.show.index')->with([
            'category' => $category,
            'products' => $products,
        ]);
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

        return view('content.admin.catalog.category.update.index')->with([
            'category' => $category,
            'categories' => $categories,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update', $this->category);

        $category = $this->category->newQuery()->findOrFail($id);


        try {
            DB::beginTransaction();

            $attributes = $request->only(['name_ru', 'name_ua', 'url', 'title_ru', 'title_ua', 'description_ru', 'description_ua', 'keywords_ru', 'keywords_ua', 'content_ru', 'content_ua']);

            if ($request->has('image')) {
                $attributes['image'] = $request->image->store('images/categories/intro', 'public');
            }

            $category->update($attributes);

            if ($category->parent_id !== (int)$request->get('parent_id')) {
                $parent = $this->category->newQuery()->where('id', $request->get('parent_id'))->first();
                $category->appendToNode($parent)->save();
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->category);

        $this->category->newQuery()->findOrFail($id)->delete();

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
