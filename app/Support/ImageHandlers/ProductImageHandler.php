<?php
/**
 * Product images handler.
 */

namespace App\Support\ImageHandlers;


use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class ProductImageHandler extends ImageHandler
{
    /**
     * @var string
     */
    const PRODUCT_IMAGE_DIRECTORY = 'images/products/';

    /**
     * @var string
     */
    const PRODUCT_DESCRIPTION_IMAGE_DIRECTORY = 'images/products/descriptions/';
    /**
     * @var array
     */
    const PRODUCT_IMAGE_TYPES = ['image', 'small', 'medium', 'large'];

    /**
     * VendorProductImage constructor.
     * @param ImageManagerStatic $imageProvider
     */
    public function __construct(ImageManagerStatic $imageProvider)
    {
        parent::__construct($imageProvider);
    }

    /**
     * Insert product model images.
     *
     * @param Product|Model $product
     * @param string $sourcePath
     * @param int $priority
     */
    public function insertProductImage(Product $product, string $sourcePath, int $priority = 1)
    {
        // create image
        $image = $this->createImageFromFile($sourcePath);

        // product images folder
        $productImagesFolder = self::PRODUCT_IMAGE_DIRECTORY . $product->id . '/';

        // $modelImageData
        $modelImageData = $this->createProductImages($image, $priority, $productImagesFolder);

        // create model
        $product->productImages()->create($modelImageData);
    }

    /**
     * Insert product description image.
     *
     * @param string $sourcePath
     * @return string
     */
    public function insertProductDescriptionImage(string $sourcePath): string
    {
        // create image
        $image = $this->createImageFromFile($sourcePath);

        // prepare image
        $resizedImage = $this->resizeImage($image, config('images.description'));

        // create image path
        $destinationPath = self::PRODUCT_DESCRIPTION_IMAGE_DIRECTORY . uniqid() . '.jpg';

        // store image
        Storage::disk('public')->put($destinationPath, $resizedImage);

        return $destinationPath;
    }

    /**
     * Delete all product images.
     *
     * @param ProductImage|Model $image
     */
    public function deleteProductImage(ProductImage $image)
    {
        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }
        if ($image->small) {
            Storage::disk('public')->delete($image->small);
        }

        if ($image->medium) {
            Storage::disk('public')->delete($image->medium);
        }

        if ($image->large) {
            Storage::disk('public')->delete($image->large);
        }
    }

    /**
     * Delete all product images.
     *
     * @param int $productId
     */
    public function deleteProductImages(int $productId)
    {
        // remove product images from storage
        Storage::disk('public')->deleteDirectory(self::PRODUCT_IMAGE_DIRECTORY . $productId);
    }

    /**
     * Delete all images of products.
     *
     * @param array $productsId
     */
    public function deleteProductsImages(array $productsId)
    {
        foreach ($productsId as $productId) {
            // remove product images from storage
            Storage::disk('public')->deleteDirectory(self::PRODUCT_IMAGE_DIRECTORY . $productId);
        }
    }

    /**
     * Create product images.
     *
     * @param Image $image
     * @param int $priority
     * @param string $productImagesFolder
     * @return array
     */
    protected function createProductImages(Image $image, int $priority, string $productImagesFolder): array
    {
        // $modelImageData
        $modelImageData = [];

        // set image priority
        $modelImageData['priority'] = $priority;

        // create each type of images
        foreach (self::PRODUCT_IMAGE_TYPES as $imageType) {

            // don't store original image
            if ($imageType === 'image' && !config('images.store_original_image')) {
                continue;
            }

            // create image path
            $imagePath = $productImagesFolder . uniqid() . '.jpg';

            // set image path for field
            $modelImageData[$imageType] = $imagePath;

            // prepare image
            $resizedImage = $this->createProductImageByType($image, $imageType);

            // store image
            Storage::disk('public')->put($imagePath, $resizedImage);

            // restore source image for next iteration
            $image->reset();
        }

        return $modelImageData;
    }

    /**
     * Create image.
     *
     * @param Image $image
     * @param string $type
     * @return mixed
     */
    protected function createProductImageByType(Image $image, string $type)
    {
        // resize image without up size
        $createdImage = $image->widen(config('images.products.' . $type), function ($constraint) {
            $constraint->upsize();
        });

        // watermark image excluding original image
        if (config('images.products.watermark') && $type !== 'image') {

            $watermark = $this->imageProvider->make(public_path('images/common/watermark.png'))
                ->widen($createdImage->width());

            $createdImage->insert($watermark, 'center');
        }

        return $createdImage->stream('jpg', 100);
    }
}
