<?php
/**
 * Created by PhpStorm.
 * User: nikeddarn
 * Date: 25.12.18
 * Time: 10:35
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
        $createdImage = $image->widen(config('shop.images.products.' . $type), function ($constraint) {
            $constraint->upsize();
        });

        // watermark image excluding original image
        if (config('shop.images.products.watermark') && $type !== 'image') {

            $watermark = $this->imageProvider->make(public_path('images/common/watermark.png'))
                ->widen($createdImage->width());

            $createdImage->insert($watermark, 'center');
        }

        return $createdImage->stream('jpg', 100);
    }
}
