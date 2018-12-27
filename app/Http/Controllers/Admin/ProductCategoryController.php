<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\VendorCategoryProduct;
use App\Models\VendorLocalCategory;
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

        $productId = $request->get('products_id');
        $categoryId = $request->get('categories_id');

        $this->validate($request, [
            'categories_id' => ['integer', Rule::unique('category_product', 'categories_id')->where('products_id', $productId), new LeafCategory()],
            'products_id' => ['integer'],
        ]);

        // attach product to category
        $this->categoryProduct->newQuery()->create($request->only(['products_id', 'categories_id']));

        // get vendor categories ids that contains processing product
        $vendorCategoryIds = $this->getProductsVendorCategories($productId);

        // attach each vendor category of product to new local category
        foreach ($vendorCategoryIds as $vendorCategoryId){
            VendorLocalCategory::query()->create([
                'vendor_categories_id' => $vendorCategoryId,
                'categories_id' => $categoryId,
            ]);
        }

        return redirect(route('admin.products.show', ['id' => $productId]));
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

        $product = $this->product->newQuery()->with('categories')->findOrFail($productId);

        if ($product->categories->count() > 1) {
            // detach product from local category
            $product->categories()->detach($categoryId);

            // get vendor categories ids that contains processing product
            $vendorCategoryIds = $this->getProductsVendorCategories($productId);

            // detach each vendor category of product from local category
            VendorLocalCategory::query()->where('categories_id', $categoryId)->whereIn('vendor_categories_id', $vendorCategoryIds)->delete();
        }

        return redirect(route('admin.products.show', ['id' => $productId]));
    }

    /**
     * Get product's vendor categories ids.
     *
     * @param int $productId
     * @return array
     */
    private function getProductsVendorCategories(int $productId): array
    {
        return VendorCategoryProduct::query()
            ->whereHas('vendorProduct', function ($query) use ($productId) {
                $query->where('products_id', $productId);
            })
            ->get()
            ->pluck('vendor_categories_id')
            ->toArray();
    }
}
