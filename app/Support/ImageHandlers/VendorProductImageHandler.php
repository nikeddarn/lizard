<?php
/**
 * Vendor product image handler.
 */

namespace App\Support\ImageHandlers;


use App\Models\Product;
use App\Support\Vendors\Providers\VendorProvider;
use Exception;
use Intervention\Image\ImageManagerStatic;

class VendorProductImageHandler extends ProductImageHandler
{
    /**
     * @var VendorProvider
     */
    private $vendorProvider;

    /**
     * VendorProductImage constructor.
     * @param ImageManagerStatic $imageProvider
     * @param VendorProvider $vendorProvider
     */
    public function __construct(ImageManagerStatic $imageProvider, VendorProvider $vendorProvider)
    {
        parent::__construct($imageProvider);

        $this->vendorProvider = $vendorProvider;
    }

    /**
     * @param Product $product
     * @param array $productImagesData
     * @throws Exception
     */
    public function insertVendorProductImages(Product $product, array $productImagesData)
    {
        // collect images data
        $imagesData = collect($productImagesData)-> keyBy('image');

        // get images uri
        $imagesPaths = $imagesData->pluck('image')->toArray();

        $successfulImagesContent = $this->vendorProvider->getPoolQueriesResponseContent($imagesPaths);

        if (empty($successfulImagesContent)){
            throw new Exception('No one product image uploaded');
        }

        // product images folder
        $productImagesFolder = self::PRODUCT_IMAGE_DIRECTORY . $product->id . '/';

        foreach ($successfulImagesContent as $imagePath => $imageContent){
            // create image
            $image = $this->createImageFromContent($imageContent);

            // get image priority
            $priority = $imagesData->get($imagePath)['priority'];

            // $modelImageData
            $modelImageData = $this->createProductImages($image, $priority, $productImagesFolder);

            // create model
            $product->productImages()->create($modelImageData);
        }
    }

    /**
     * Create image from content.
     *
     * @param string $sourcePath
     * @return \Intervention\Image\Image
     */
    private function createImageFromContent(string $sourcePath)
    {
        $image = $this->imageProvider->make($sourcePath);

        $image->backup();

        return $image;
    }
}
