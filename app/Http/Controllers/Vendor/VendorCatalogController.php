<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Support\Vendors\VendorBroker;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class VendorCatalogController extends Controller
{
    /**
     * @var Vendor
     */
    private $vendor;
    /**
     * @var VendorCategory
     */
    private $vendorCategory;
    /**
     * @var VendorBroker
     */
    private $vendorBroker;

    /**
     * VendorCatalogController constructor.
     * @param Vendor $vendor
     * @param VendorCategory $vendorCategory
     * @param VendorBroker $vendorBroker
     */
    public function __construct(Vendor $vendor, VendorCategory $vendorCategory, VendorBroker $vendorBroker)
    {
        $this->vendor = $vendor;
        $this->vendorCategory = $vendorCategory;
        $this->vendorBroker = $vendorBroker;
    }

    /**
     * Show vendor categories list.
     *
     * @param int $vendorId
     * @return View
     */
    public function categoriesTree(int $vendorId): View
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        try {
            $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

            // synchronized vendor categories
            $synchronizedVendorCategories = $this->vendorCategory->newQuery()->where('vendors_id', $vendorId)->get()->keyBy('vendor_category_id');

            // try to get categories from vendor
            $vendorCategories = $this->vendorBroker->getVendorCatalogManager($vendorId)->getVendorCategoriesTree();

        } catch (Exception $exception) {
            return view('content.admin.vendors.catalog.tree.index')
                ->with(compact('vendor'))->withErrors([$exception->getMessage()]);
        }

        return view('content.admin.vendors.catalog.tree.index')
            ->with(compact('vendor', 'synchronizedVendorCategories', 'vendorCategories'));
    }

    /**
     * Show products of vendor category.
     *
     * @param int $vendorId
     * @param int $vendorCategoryId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function categoryProducts(int $vendorId, int $vendorCategoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        try {
            // define current page
            $page = request()->has('page') ? request()->get('page') : 1;

            // get vendor
            $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

            $synchronizedVendorCategory = $this->vendorCategory->newQuery()->where([
                ['vendors_id', '=', $vendorId],
                ['vendor_category_id', '=', $vendorCategoryId],
            ])->first();

            if ($synchronizedVendorCategory) {
                $vendorCategoryName = $synchronizedVendorCategory->name;
                $vendorCategoriesId = $synchronizedVendorCategory->id;
            }else{
                // get vendor category data
                $vendorCategory = $this->vendorBroker->getVendorCatalogManager($vendorId)->getVendorCategoryData($vendorCategoryId);
                $vendorCategoryName = $vendorCategory->name;
                $vendorOwnCategoryId = $vendorCategory->id;
            }

            // get paginator for current page
            $vendorCategoryProducts = $this->vendorBroker->getVendorCatalogManager($vendorId)->getCategoryPageProducts($vendorCategoryId, $page);

        } catch (Exception $exception) {
            return view('content.admin.vendors.category.products.index')
                ->withErrors([$exception->getMessage()]);
        }

        return view('content.admin.vendors.catalog.products.index')->with(compact('vendor', 'vendorCategoryName', 'vendorCategoriesId', 'vendorOwnCategoryId',  'vendorCategoryProducts'));
    }
}
