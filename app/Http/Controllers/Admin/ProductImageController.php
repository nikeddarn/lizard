<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Support\ImageHandlers\ProductImageHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(string $id)
    {
        $this->authorize('create', $this->productImage);

        $product = $this->product->newQuery()->findOrFail($id);

        return view('content.admin.catalog.product_image.create.index')->with([
            'product' => $product,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ProductImageHandler $imageHandler
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, ProductImageHandler $imageHandler)
    {
        $this->authorize('create', $this->productImage);

        $this->validate($request, [
            'image' => 'image'
        ]);

        $product = $this->product->newQuery()->findOrFail($request->get('products_id'));

        $this->insertProductImage($imageHandler, $request->image, $product);

        return redirect(route('admin.products.show', ['id' => $product->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->productImage);

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
        foreach (['image', 'small', 'medium', 'large'] as $type) {
            Storage::disk('public')->delete($image->$type);
        }

        // delete image
        $image->delete();

        return redirect(route('admin.products.show', ['id' => $productId]));

    }

    /**
     * Set given image as priority.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function priority(string $id)
    {
        $this->authorize('update', $this->productImage);

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

    /**
     * Create and store images.
     *
     * @param ProductImageHandler $imageHandler
     * @param string $sourcePath
     * @param Product|Model $product
     */
    private function insertProductImage(ProductImageHandler $imageHandler, string $sourcePath, Product $product)
    {
        // define priority
        $priority = $product->productImages()->where('priority', 1)->count() ? 0 : 1;

        // create product images
        $imageHandler->insertProductImage($product, $sourcePath, $priority);
    }
}
