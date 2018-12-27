<?php
/**
 * Product images handler.
 */

namespace App\Support\ImageHandlers;


use App\Models\Product;
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
     * @param Product $product
     * @param string $sourcePath
     * @param int $priority
     */
    public function insertProductImage(Product $product, string $sourcePath, int $priority)
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
        $resizedImage = $this->resizeImage($image, config('shop.images.description'));

        // create image path
        $destinationPath = self::PRODUCT_DESCRIPTION_IMAGE_DIRECTORY . uniqid() . '.jpg';

        // store image
        Storage::disk('public')->put($destinationPath, $resizedImage);

        return $destinationPath;
    }

    /**
     * Delete all product images.
     *
     * @param int $productId
     */
    public function deleteProductImage(int $productId)
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
     * Create image from file.
     *
     * @param string $sourcePath
     * @return Image
     */
    private function createImageFromFile(string $sourcePath)
    {
        $image = $this->imageProvider->make($sourcePath);

        $image->backup();

        return $image;
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
            if ($imageType === 'image' && !config('shop.images.store_original_image')) {
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
            $image->invert();
        }

        return $modelImageData;
    }
}
