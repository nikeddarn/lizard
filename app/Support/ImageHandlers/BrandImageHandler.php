<?php
/**
 * Category image handler.
 */

namespace App\Support\ImageHandlers;


use Illuminate\Support\Facades\Storage;

class BrandImageHandler extends ImageHandler
{
    /**
     * @var string
     */
    const BRAND_IMAGE_DIRECTORY = 'images/brands/';

    /**
     * Create user avatar.
     *
     * @param string $sourcePath
     * @param string $destinationPath
     */
    public function createBrandImage(string $sourcePath, string $destinationPath)
    {
        // create image
        $image = $this->createImageFromFile($sourcePath);

        // resize image
        $resizedImage = $this->resizeImage($image, config('images.brand'));

        // store image
        Storage::disk('public')->put($destinationPath, $resizedImage);
    }

    /**
     * Delete brand image directory.
     *
     * @param int $brandId
     */
    public function deleteBrandImage(int $brandId)
    {
        Storage::disk('public')->deleteDirectory(self::BRAND_IMAGE_DIRECTORY . $brandId);
    }
}
