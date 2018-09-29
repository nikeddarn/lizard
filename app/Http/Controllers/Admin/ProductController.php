<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var Category
     */
    private $category;

    /**
     * CategoryController constructor.
     * @param Product $product
     * @param Category $category
     */
    public function __construct(Product $product, Category $category)
    {
        $this->authorizeResource(Product::class);

        $this->product = $product;
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // make query
        $productQuery = $this->product->newQuery()->join('categories', 'categories.id', '=', 'products.categories_id')->with('category');

        // add order by constraints
        if (request()->has('order')){
            if (request('order') === 'product'){
                $productQuery->orderByDesc('product.name_' . app()->getLocale());
            }elseif (request('order') === 'category'){
                $productQuery->orderByDesc('categories.name_' . app()->getLocale());
            }
        }

        return view('content.admin.catalog.product.list.index')->with([
            'products' => $productQuery->paginate(config('admin.show_items_per_page')),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->category->withDepth()->get();

        // redirect to create category if no one category exists
        if (!$categories->count()){
            return redirect(route('admin.categories.create'))->withErrors(['no_categories' => trans('validation.no_categories')]);
        }

        return view('content.admin.catalog.product.create.index')->with([
            'categories' => $categories->toTree(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        var_dump($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

        return '/storage/' .  $request->image->store('images/products/content', 'public');
    }
}
