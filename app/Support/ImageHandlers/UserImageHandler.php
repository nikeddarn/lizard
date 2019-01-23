<?php
/**
 * User image handler.
 */

namespace App\Support\ImageHandlers;


use Illuminate\Support\Facades\Storage;

class UserImageHandler extends ImageHandler
{
    /**
     * Create user avatar.
     *
     * @param string $sourcePath
     * @param string $destinationPath
     */
    public function createUserAvatar(string $sourcePath, string $destinationPath)
    {
        // create image
        $image = $this->createImageFromFile($sourcePath);

        // resize image
        $resizedImage = $this->resizeImage($image, config('images.user.avatar_width'));

        // store image
        Storage::disk('public')->put($destinationPath, $resizedImage);
    }
}
