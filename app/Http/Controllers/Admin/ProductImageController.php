<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->productImage);

        $this->validate($request, [
            'image' => 'image'
        ]);

        $product = $this->product->newQuery()->findOrFail($request->get('products_id'));

        $hasProductPrimaryImage = (bool)$product->primaryImage()->count();

        $product->productImages()->create([
            'image' => $request->image->store('images/products', 'public'),
            'priority' => !$hasProductPrimaryImage,
        ]);

        return redirect(route('admin.products.show', ['id' => $product->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', $this->productImage);

        $image = $this->productImage->newQuery()->findOrFail($id);

        $productsId = $image->products_id;

        if ($image->priority){
            $firstNotPrimaryImage = $this->productImage->newQuery()->where(['products_id' => $productsId, 'priority' => 0])->first();
            $firstNotPrimaryImage->priority = 1;
            $firstNotPrimaryImage->save();
        }

        $image->delete();

        return redirect(route('admin.products.show', ['id' => $productsId]));

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

        if ($oldPriorityImage){
            $oldPriorityImage->priority = 0;
            $oldPriorityImage->save();
        }

        $newPriorityImage->priority = 1;
        $newPriorityImage->save();

        return redirect(route('admin.products.show', ['id' => $productsId]));
    }
}
