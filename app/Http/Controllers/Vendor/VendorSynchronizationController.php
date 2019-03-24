<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Vendor\SyncTypeInterface;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class VendorSynchronizationController extends Controller
{
    /**
     * @var Vendor
     */
    private $vendor;
    /**
     * @var VendorBroker
     */
    private $broker;
    /**
     * @var VendorCategory
     */
    private $vendorCategory;

    /**
     * VendorSynchronizationController constructor.
     * @param Vendor $vendor
     * @param VendorCategory $vendorCategory
     * @param VendorBroker $broker
     */
    public function __construct(Vendor $vendor, VendorCategory $vendorCategory, VendorBroker $broker)
    {
        $this->vendor = $vendor;
        $this->broker = $broker;
        $this->vendorCategory = $vendorCategory;
    }

    /**
     * Show synchronized categories.
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function synchronizedCategories()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        //get locale
        $locale = app()->getLocale();

        // get synchronized categories with products count
        $synchronizedCategories = $this->vendorCategory->newQuery()
            ->selectRaw("vendor_categories.name_$locale AS vendor_category_name, categories.name_$locale AS local_category_name, categories.url AS local_category_url, COUNT(DISTINCT(products1.id)) AS products_count, COUNT(DISTINCT(products2.id)) AS published_products_count, vendor_local_categories.auto_add_new_products AS auto_add_products, vendors.name_$locale AS vendor_name, vendors.id AS vendor_id, vendor_categories.id AS vendor_category_id, categories.id AS local_category_id, vendor_categories.vendor_category_id AS own_vendor_category_id")
            ->join('vendors', 'vendors.id', '=', 'vendor_categories.vendors_id')
            ->leftJoin('vendor_category_product', 'vendor_category_product.vendor_categories_id', '=', 'vendor_categories.id')
            ->leftJoin('vendor_products', 'vendor_category_product.vendor_products_id', '=', 'vendor_products.id')
            ->leftJoin('products AS products1', 'products1.id', '=', 'vendor_products.products_id')
            ->join('vendor_local_categories', 'vendor_local_categories.vendor_categories_id', '=', 'vendor_categories.id')
            ->join('categories', 'vendor_local_categories.categories_id', '=', 'categories.id')
            ->leftJoin('category_product', 'category_product.categories_id', '=', 'categories.id')
            ->leftJoin('products  AS products2', function ($join) {
                $join->on('category_product.products_id', '=', 'products2.id');
                $join->on('products1.id', '=', 'products2.id');
                $join->where('products2.published', '=', 1);
            })
            ->groupBy("vendor_categories.id", "categories.id", "vendor_local_categories.auto_add_new_products", "vendors.name_$locale", "vendor_categories.id", "categories.id", "vendors.id", "vendor_categories.vendor_category_id")
            ->orderByRaw('vendor_name, vendor_category_name')
            ->paginate(config('admin.show_items_per_page'));

        return view('content.admin.vendors.synchronization.synchronized.index')->with(compact('synchronizedCategories'));
    }

    /**
     * Get vendors with counts of queued jobs.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function synchronizationQueue(Request $request)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $locale = app()->getLocale();

        $insertProductSyncType = SyncTypeInterface::INSERT_PRODUCT;
        $updateProductPriceSyncType = SyncTypeInterface::UPDATE_PRODUCT;

        $vendors = $this->vendor->newQuery()
            ->leftJoin('synchronizing_products AS inserting_products', function ($join) use ($insertProductSyncType) {
                $join->on('inserting_products.vendors_id', '=', 'vendors.id');
                $join->where('inserting_products.sync_type', '=', $insertProductSyncType);
            })
            ->leftJoin('synchronizing_products AS updating_products', function ($join) use ($updateProductPriceSyncType) {
                $join->on('updating_products.vendors_id', '=', 'vendors.id');
                $join->where('updating_products.sync_type', '=', $updateProductPriceSyncType);
            })
            ->selectRaw("vendors.id, vendors.name_$locale, vendors.sync_new_products_at, vendors.sync_prices_at")
            ->selectRaw('COUNT(DISTINCT(inserting_products.vendor_product_id)) AS inserting_products_count')
            ->selectRaw('COUNT(DISTINCT(updating_products.vendor_product_id)) AS updating_products_count')
            ->groupBy('vendors.id')
            ->orderBy("vendors.name_$locale")
            ->get();

        // prepare data
//        $this->prepareVendorsData($vendors);

        if ($request->ajax()) {
            return view('content.admin.vendors.synchronization.queue.parts.list')->with(compact('vendors'));
        } else {
            return view('content.admin.vendors.synchronization.queue.index')->with(compact('vendors'));
        }
    }

    /**
     * Synchronize given vendor.
     *
     * @param int $vendorId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function synchronize(int $vendorId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        // retrieve vendor
        $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

        try {
            // sync new vendor products
            $this->syncNewVendorProducts($vendor);

            // sync updated vendor products
            $this->syncUpdatedVendorProducts($vendor);
        } catch (Exception $exception) {
            Log::channel('schedule')->info($exception->getMessage());
            return back()->withErrors([$exception->getMessage()]);
        }

        return back();
    }

    /**
     * Sync new vendor products.
     *
     * @param Vendor|Model $vendor
     * @throws Exception
     */
    private function syncNewVendorProducts(Vendor $vendor)
    {
        // get manager
        $syncNewProductManager = $this->broker->getSyncNewProductManager($vendor->id);

        // sync product prices
        $syncNewProductManager->synchronizeNewProducts($vendor);
    }

    /**
     * Sync updated vendor products.
     *
     * @param Vendor|Model $vendor
     * @throws Exception
     */
    private function syncUpdatedVendorProducts(Vendor $vendor)
    {
        // get manager
        $syncUpdatedProductManager = $this->broker->getSyncUpdatedProductManager($vendor->id);

        // sync product prices
        $syncUpdatedProductManager->synchronizeUpdatedProducts($vendor);
    }
}
