<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Rules\LeafCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var CategoryProduct
     */
    private $categoryProduct;
    /**
     * @var Category
     */
    private $category;

    /**
     * ProductFilterController constructor.
     * @param CategoryProduct $categoryProduct
     * @param Product $product
     * @param Category $category
     */
    public function __construct(CategoryProduct $categoryProduct, Product $product, Category $category)
    {
        $this->product = $product;
        $this->categoryProduct = $categoryProduct;
        $this->category = $category;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(string $id)
    {
        $this->authorize('create', $this->categoryProduct);

        $product = $this->product->newQuery()->findOrFail($id);

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        return view('content.admin.catalog.product_category.create.index')->with(compact('product', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->categoryProduct);

        $productsId = $request->get('products_id');

        $this->validate($request, [
            'categories_id' => ['integer', Rule::unique('category_product', 'categories_id')->where('products_id', $productsId), new LeafCategory()],
            'products_id' => ['integer'],
        ]);


        $this->categoryProduct->newQuery()->create($request->only(['products_id', 'categories_id']));

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $this->authorize('delete', $this->categoryProduct);

        $productId = $request->get('products_id');
        $categoryId = $request->get('categories_id');

        $product = $this->product->newQuery()->withCount('categoryProducts')->findOrFail($productId);

        if ($product->category_products_count > 1){
            $product->categories()->detach($categoryId);
        }

        return redirect(route('admin.products.show', ['id' => $productId]));
    }
}