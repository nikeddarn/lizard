<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductCategory\StoreProductCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\VendorCategoryProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ProductCategoryController extends Controller
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
     * ProductFilterController constructor.
     * @param Product $product
     * @param Category $category
     */
    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function create(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $product = $this->product->newQuery()->findOrFail($id);

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        return view('content.admin.catalog.product_category.create.index')->with(compact('product', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductCategoryRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $productId = $request->get('products_id');
        $categoryId = $request->get('categories_id');

        $category = $this->category->newQuery()->findOrFail($categoryId);

        // attach product to category
        $category->products()->syncWithoutDetaching([$productId]);

        // get vendor categories ids that contains processing product
        $vendorCategoryIds = $this->getProductsVendorCategories($productId);

        // attach each vendor category of product to new local category
        $category->vendorCategories()->syncWithoutDetaching($vendorCategoryIds);

        return redirect(route('admin.products.show', ['id' => $productId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $productId = $request->get('products_id');
        $categoryId = $request->get('categories_id');

        $product = $this->product->newQuery()->with('categories')->findOrFail($productId);

        // detach product from local category
        $product->categories()->detach($categoryId);

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
