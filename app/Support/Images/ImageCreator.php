<?php
/**
 * Create images.
 **/

namespace App\Support\Images;


use Exception;

class ImageCreator
{
    /**
     * Create small image stream.
     *
     * @param string $path
     * @return bool|null|resource
     */
    public function createSmallImageResource(string $path)
    {
        try {
            $image = $this->createImageResource($path);

            $imageStream = fopen('php://memory', 'w+');

            $smallImage = $this->resizeImage($image, config('shop.image.width.small'));

            imagejpeg($smallImage, $imageStream, 100);

            return $imageStream;

        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Create medium image stream.
     *
     * @param string $path
     * @return bool|null|resource
     */
    public function createMediumImageResource(string $path)
    {
        try {
            $image = $this->createImageResource($path);

            $imageStream = fopen('php://memory', 'w+');

            $smallImage = $this->resizeImage($image, config('shop.image.width.medium'));

            imagejpeg($smallImage, $imageStream, 100);

            return $imageStream;

        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Create large image stream.
     *
     * @param string $path
     * @return bool|null|resource
     */
    public function createLargeImageResource(string $path)
    {
        try {
            $image = $this->createImageResource($path);

            $imageStream = fopen('php://memory', 'w+');

            $smallImage = $this->resizeImage($image, config('shop.image.width.large'));

            imagejpeg($smallImage, $imageStream, 100);

            return $imageStream;

        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * Create image from uploaded image path.
     *
     * @param string $path
     * @return resource
     * @throws Exception
     */
    private function createImageResource(string $path)
    {
        switch (exif_imagetype($path)) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($path);
                break;

            case IMAGETYPE_PNG:
                $image =  imagecreatefrompng($path);
                break;

            case IMAGETYPE_GIF:
                $image =  imagecreatefromgif($path);
                break;

            default:
                throw new Exception('Unsupported image type');
        }

        if (config('shop.image.rotate') && imagesx($image) > imagesy($image)) {
            $image = imagerotate($image, 90, 0);
        }

        if (config('shop.image.watermark.use')){
            $image = $this->watermarkImage($image);
        }

        return $image;
    }

    /**
     * Add watermark to image.
     *
     * @param $image
     * @return resource
     */
    private function watermarkImage($image)
    {
        $text = config('app.name');
        $font = public_path('fonts/OpenSans-Regular.ttf');
        $color = imagecolorallocatealpha($image, config('shop.image.watermark.color.r'), config('shop.image.watermark.color.g'), config('shop.image.watermark.color.b'), config('shop.image.watermark.color.a'));

        // starting text size
        $textSize = imagesx($image) * 2 / strlen($text);
        // define text size
        do {
            $box = imageftbbox($textSize, 0, $font, $text);
            $fitLength = imagesx($image) * (1- config('shop.image.watermark.left') * 2);
            $textLength = $box[4] - $box[0];
            $textSize --;
        }while($textLength > $fitLength);

        $textLeft = imagesx($image) * config('shop.image.watermark.left');
        $textTop = imagesy($image) * config('shop.image.watermark.top');

        imagettftext($image, $textSize, 0, $textLeft, $textTop, $color, $font, $text);

        return $image;
    }

    /**
     * Resize image.
     *
     * @param $image
     * @param int $width
     * @return resource
     */
    private function resizeImage($image, int $width)
    {
        if (config('shop.image.ratio.use')){
            $ratio = config('shop.image.ratio.value');
        }else{
            $ratio = imagesy($image) / imagesx($image);
        }

        $height = $width * $ratio;

        $resizedImage = imagecreatetruecolor($width, $height);

        imagecopyresized($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

        return $resizedImage;
    }
}