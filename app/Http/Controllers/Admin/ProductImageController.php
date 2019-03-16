<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductImage\StoreProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ProductImageController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductImage
     */
    private $productImage;

    /**
     * ProductImageController constructor.
     * @param Product $product
     * @param ProductImage $productImage
     */
    public function __construct(Product $product, ProductImage $productImage)
    {
        $this->product = $product;
        $this->productImage = $productImage;
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

        return view('content.admin.catalog.product_image.create.index')->with([
            'product' => $product,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductImageRequest $request
     * @param ProductImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductImageRequest $request, ProductImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $product = $this->product->newQuery()->findOrFail($request->get('products_id'));

        // define priority
        $priority = $product->productImages()->where('priority', 1)->count() ? 0 : 1;

        // create product images
        $imageHandler->insertProductImage($product, $request->image, $priority);

        return redirect(route('admin.products.show', ['id' => $product->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @param ProductImageHandler $imageHandler
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(string $id, ProductImageHandler $imageHandler)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $image = $this->productImage->newQuery()->findOrFail($id);

        $productId = $image->products_id;

        // set first image as priority if removing image was priority
        if ($image->priority) {
            $firstNotPrimaryImage = $this->productImage->newQuery()->where(['products_id' => $productId, 'priority' => 0])->first();
            if ($firstNotPrimaryImage) {
                $firstNotPrimaryImage->priority = 1;
                $firstNotPrimaryImage->save();
            }
        }

        // remove images from storage
        $imageHandler->deleteProductImage($productId);

        // delete image
        $image->delete();

        return redirect(route('admin.products.show', ['id' => $productId]));

    }

    /**
     * Set given image as priority.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function priority(string $id)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $newPriorityImage = $this->productImage->newQuery()->findOrFail($id);

        $productsId = $newPriorityImage->products_id;

        $oldPriorityImage = $this->productImage->newQuery()->where(['products_id' => $newPriorityImage->products_id, 'priority' => 1])->first();

        if ($oldPriorityImage) {
            $oldPriorityImage->priority = 0;
            $oldPriorityImage->save();
        }

        $newPriorityImage->priority = 1;
        $newPriorityImage->save();

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }
}
