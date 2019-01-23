<?php
/**
 * Category image handler.
 */

namespace App\Support\ImageHandlers;


use Illuminate\Support\Facades\Storage;

class CategoryImageHandler extends ImageHandler
{
    /**
     * @var string
     */
    const CATEGORY_IMAGE_DIRECTORY = 'images/categories/';

    /**
     * Create user avatar.
     *
     * @param string $sourcePath
     * @param string $destinationPath
     */
    public function createCategoryIcon(string $sourcePath, string $destinationPath)
    {
        // create image
        $image = $this->createImageFromFile($sourcePath);

        // resize image
        $resizedImage = $this->resizeImage($image, config('images.category'));

        // store image
        Storage::disk('public')->put($destinationPath, $resizedImage);
    }

    /**
     * Delete category image directory.
     *
     * @param int $categoryId
     */
    public function deleteCategoryIcon(int $categoryId)
    {
        Storage::disk('public')->deleteDirectory(self::CATEGORY_IMAGE_DIRECTORY . $categoryId);
    }
}
