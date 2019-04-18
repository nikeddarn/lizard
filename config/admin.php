<?php
/**
 * Admin settings.
 */

return [
    // items count per page on admin pages
    'show_items_per_page' => 50,

    // sub items count per page on admin pages
    'show_item_properties_per_page' => 12,

    // vendor products per page limit
    'vendor_products_per_page' => 50,

    // archive products
    'archive_products' => [
        // sync archive products ?
        'sync' => false,
        // show archive products to user ?
        'show' => false,
    ],
];
