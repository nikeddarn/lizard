<?php
/**
 * User image handler.
 */

namespace App\Support\ImageHandlers;


use Illuminate\Support\Facades\Storage;

class SliderImageHandler extends ImageHandler
{
    /**
     * @var string
     */
    const SLIDER_IMAGE_FOLDER = 'images/slider/';

    /**
     * Create user avatar.
     *
     * @param int $sliderId
     * @param string $sourcePath
     * @return string
     */
    public function createSliderImage(int $sliderId, string $sourcePath):string
    {
        // destination image path
        $slideDestinationPath = self::SLIDER_IMAGE_FOLDER . $sliderId . '/' . uniqid() . '.jpg';

        // create image
        $image = $this->createImageFromFile($sourcePath);

        // resize image
        $resizedImage = $this->resizeImage($image, config('images.main_slider_width'));

        // store image
        Storage::disk('public')->put($slideDestinationPath, $resizedImage);

        return $slideDestinationPath;
    }

    /**
     * Delete category image directory.
     *
     * @param string $image
     */
    public function deleteSliderImage(string $image)
    {
        Storage::disk('public')->delete($image);
    }
}
