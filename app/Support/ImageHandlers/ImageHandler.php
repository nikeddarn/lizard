<?php
/**
 * Image handler.
 */

namespace App\Support\ImageHandlers;


use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;

class ImageHandler
{
    /**
     * @var ImageManagerStatic
     */
    protected $imageProvider;

    /**
     * ProductImageUploader constructor.
     * @param ImageManagerStatic $imageProvider
     */
    public function __construct(ImageManagerStatic $imageProvider)
    {
        $this->imageProvider = $imageProvider;
    }

    /**
     * Create image from file.
     *
     * @param string $sourcePath
     * @return Image
     */
    protected function createImageFromFile(string $sourcePath)
    {
        $image = $this->imageProvider->make($sourcePath);

        $image->backup();

        return $image;
    }

    /**
     * Create image.
     *
     * @param Image $image
     * @param int $width
     * @return mixed
     */
    protected function resizeImage(Image $image, int $width)
    {
        // resize image without up size
        $createdImage = $image->widen($width, function ($constraint) {
            $constraint->upsize();
        });

        return $createdImage->stream('jpg', 100);
    }
}
