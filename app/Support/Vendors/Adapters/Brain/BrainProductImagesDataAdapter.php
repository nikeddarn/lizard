<?php
/**
 * Create vendor product images data.
 */

namespace App\Support\Vendors\Adapters\Brain;

use stdClass;

class BrainProductImagesDataAdapter
{
    /**
     * @var array
     */
    const VENDOR_IMAGE_PLACEHOLDER_PATHS = [
        'http://brain.com.ua/static/common/images/no-photo-api.png',
    ];

    const IMAGE_TYPE_KEYS = ['full_image', 'large_image', 'medium_image', 'small_image'];

    /**
     * Prepare vendor product's images data.
     *
     * @param array $imagesData
     * @return array
     */
    public function prepareVendorProductImagesData(array $imagesData): array
    {
        $productImages = [];

        // define min image priority of all product images
        $minImagesPriority = collect($imagesData)->min('priority');

        foreach ($imagesData as $imageData) {

            // define image priority
            $priority = (int)$imageData->priority === $minImagesPriority ? 1 : 0;

            // define image path
            $imagePath = $this->getRealImagePath($imageData);

            if ($imagePath){
                $productImages[] = [
                    'image' => $imagePath,
                    'priority' => $priority,
                ];
            }
        }

        return $productImages;
    }

    /**
     * Get the biggest real product image path.
     *
     * @param stdClass $imageData
     * @return string
     */
    private function getRealImagePath(stdClass $imageData):string
    {
        foreach (self::IMAGE_TYPE_KEYS as $imageType) {
            // get image path
            $imageTypePath = $imageData->$imageType;

            // path not empty and not image placeholder
            if ($imageTypePath && !in_array($imageTypePath, self::VENDOR_IMAGE_PLACEHOLDER_PATHS)) {
                return $imageTypePath;
            }
        }

        return '';
    }
}
