<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use App\Support\Images\ImageCreator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
     * @var ImageCreator
     */
    private $imageCreator;

    /**
     * ProductImageController constructor.
     * @param Product $product
     * @param ProductImage $productImage
     * @param ImageCreator $imageCreator
     */
    public function __construct(Product $product, ProductImage $productImage, ImageCreator $imageCreator)
    {

        $this->product = $product;
        $this->productImage = $productImage;
        $this->imageCreator = $imageCreator;
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->productImage);

        $this->validate($request, [
            'image' => 'image'
        ]);

        $product = $this->product->newQuery()->findOrFail($request->get('products_id'));

        $this->insertProductImages($request, $product);

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

        if ($image->priority) {
            $firstNotPrimaryImage = $this->productImage->newQuery()->where(['products_id' => $productsId, 'priority' => 0])->first();
            if ($firstNotPrimaryImage) {
                $firstNotPrimaryImage->priority = 1;
                $firstNotPrimaryImage->save();
            }
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
     * @param Request $request
     * @param Product|Model $product
     */
    public function insertProductImages(Request $request, Product $product)
    {
        $image = $request->image;

        $imagesDirectory = 'images/products/' . $product->id . '/';

        // original image
        $productImage['image'] = Storage::disk('public')->putFile($imagesDirectory, $image);

        // small image
        $productImage['small'] = $imagesDirectory . uniqid() . '.jpg';
        Storage::disk('public')->put($productImage['small'], $this->createImage($image, config('shop.images.products.small'))->stream('jpg', 100));

        // medium image
        $productImage['medium'] = $imagesDirectory . uniqid() . '.jpg';
        Storage::disk('public')->put($productImage['medium'], $this->createImage($image, config('shop.images.products.medium'))->stream('jpg', 100));

        // large image
        $productImage['large'] = $imagesDirectory . uniqid() . '.jpg';
        Storage::disk('public')->put($productImage['large'], $this->createImage($image, config('shop.images.products.large'))->stream('jpg', 100));

        $product->productImages()->create($productImage);
    }

    /**
     * Create image.
     *
     * @param $image
     * @param $imageSizes
     * @return mixed
     */
    private function createImage($image, $imageSizes)
    {
        $createdImage = Image::make($image);
        $createdImage->resize($imageSizes['w'], $imageSizes['h']);

        if (config('shop.images.products.watermark')) {

            $watermarkConfig = config('shop.images.watermark');

            $imageText = config('app.name');
            $baseX = $imageSizes['w'] * $watermarkConfig['baseX'];
            $baseY = $imageSizes['h'] * $watermarkConfig['baseY'];
            $fontSize = ($imageSizes['w'] - $baseX * 2) / strlen($imageText) * 2;

            $createdImage->text($imageText, $baseX, $baseY, function ($font) use ($watermarkConfig, $fontSize) {
                $font->file(public_path($watermarkConfig['font']));
                $font->size($fontSize);
                $font->color($watermarkConfig['color']);
            });
        }

        return $createdImage;
    }
}
