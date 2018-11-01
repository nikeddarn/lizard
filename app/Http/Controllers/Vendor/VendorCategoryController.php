<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\VendorCategory;
use App\Http\Controllers\Controller;

class VendorCategoryController extends Controller
{
    /**
     * @var Category
     */
    private $vendorCategory;

    /**
     * VendorCategoryController constructor.
     * @param VendorCategory $vendorCategory
     */
    public function __construct(VendorCategory $vendorCategory)
    {
        $this->vendorCategory = $vendorCategory;
    }

    public function index()
    {
        $locale = app()->getLocale();

        $vendorCategories = $this->vendorCategory->newQuery()
            ->join('vendors', 'vendors.id', '=', 'vendor_categories.vendors_id')
            ->join('vendor_products', 'vendor_products.vendor_categories_id', '=', 'vendor_categories.id')
            ->join('products', 'products.id', '=', 'vendor_products.products_id')
            ->join('categories', 'categories.id', '=', 'products.categories_id')
            ->selectRaw("DISTINCT(vendor_categories.name_$locale as vendor_category_name, categories.name_$locale as category_name), vendors.name_$locale")
            ->orderByRaw('vendors.name_$locale')
            ->get();
    }
}
