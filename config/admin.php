<?php
/**
 * Admin settings.
 */

use App\Contracts\Vendor\VendorInterface;

return [
    // items count per page on admin pages
    'show_items_per_page' => 24,

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

    // session id ttl (minutes)
    'vendor_session_id_ttl' => [
        VendorInterface::BRAIN => 30,
    ],
];
