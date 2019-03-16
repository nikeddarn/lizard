<?php
/**
 * Images config data.
 */

return [
    // store original full vendor image
    'store_original_image' => false,

    // sizes for product image by types
    'products' => [
        // original image
        'image' => 2600,
        // shop images
        'small' => 100,
        'medium' => 400,
        'large' => 1200,

        // use watermark
        'watermark' => false,
    ],

    // category image size
    'category' => 300,

    // brand image size
    'brand' => 400,

    // description images
    'description' => 800,

    'user' => [
        // max uploading file size for user's avatar
        'avatar_max_filesize' => 10,

        // stored user avatar's width
        'avatar_width' => 400,
    ],

    'main_slider_width' => 1920,
];
