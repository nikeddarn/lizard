<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreCategoryRequest;
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
        $this->authorizeResource(Category::class);

        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.admin.catalog.category.list.index')->with([
            'categories' => $this->category->withDepth()->get()->toTree(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.admin.catalog.category.create.index')->with([
            'categories' => $this->category->withDepth()->get()->toTree(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $attributes = $request->only(['name_ru', 'name_ua', 'url', 'parent_id', 'title_ru', 'title_ua', 'description_ru', 'description_ua', 'keywords_ru', 'keywords_ua', 'content_ru', 'content_ua']);

        // set parent_id to null if category is root
        if ($attributes['parent_id'] === '0'){
            $attributes['parent_id'] = null;
        }

        // upload category image
        if ($request->hasFile('image')){
            $attributes['image'] = $request->image->store('images/categories/intro', 'public');
        }

        // create category
        $this->category->create($attributes);

        return redirect(route('admin.categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        var_dump(request()->all());
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

        $this->validate($request, ['image' => 'mimes:jpeg,bmp,png,gif']);

        return '/storage/' .  $request->image->store('images/categories/content', 'public');
    }
}
