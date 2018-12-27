<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Vendor\SyncTypeInterface;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Support\Vendors\VendorBroker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
     * VendorSynchronizationController constructor.
     * @param Vendor $vendor
     * @param VendorBroker $broker
     */
    public function __construct(Vendor $vendor, VendorBroker $broker)
    {
        $this->vendor = $vendor;
        $this->broker = $broker;
    }

    /**
     * Get vendors with counts of queued jobs.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale();

        $insertProductSyncType = SyncTypeInterface::INSERT_PRODUCT;
        $updateProductPriceSyncType = SyncTypeInterface::UPDATE_PRODUCT;

        $vendors = $this->vendor->newQuery()
            ->leftJoin('synchronizing_products AS ' . $insertProductSyncType, function ($join) use ($insertProductSyncType) {
                $join->on($insertProductSyncType . '.vendors_id', '=', 'vendors.id');
                $join->where($insertProductSyncType . '.sync_type', '=', $insertProductSyncType);
            })
            ->leftJoin('synchronizing_products AS ' . $updateProductPriceSyncType, function ($join) use ($updateProductPriceSyncType) {
                $join->on($updateProductPriceSyncType . '.vendors_id', '=', 'vendors.id');
                $join->where($updateProductPriceSyncType . '.sync_type', '=', $updateProductPriceSyncType);
            })
            ->selectRaw("vendors.id, vendors.name_$locale, vendors.sync_new_products_at, vendors.sync_prices_at")
            ->selectRaw('COUNT(DISTINCT(' . $insertProductSyncType . '.vendor_product_id)) AS ' . $insertProductSyncType . '_count')
            ->selectRaw('COUNT(DISTINCT(' . $updateProductPriceSyncType . '.vendor_product_id)) AS ' . $updateProductPriceSyncType . '_count')
            ->groupBy('vendors.id')
            ->orderBy("vendors.name_$locale")
            ->get();

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
        // retrieve vendor
        $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

        // sync new vendor products
        $this->syncNewVendorProducts($vendor);

        // sync updated vendor products
        $this->syncUpdatedVendorProducts($vendor);

        return back();
    }

    /**
     * Sync new vendor products.
     *
     * @param Vendor|Model $vendor
     */
    private function syncNewVendorProducts(Vendor $vendor)
    {
        // get manager
        $syncNewProductManager = $this->broker->getSyncNewProductManager($vendor->id);

        // sync product prices
        $vendorSyncAt = $syncNewProductManager->synchronizeNewProducts($vendor->sync_new_products_at);

        // update vendor's synchronized_at
        $vendor->sync_new_products_at = $vendorSyncAt;
        $vendor->save();
    }

    /**
     * Sync updated vendor products.
     *
     * @param Vendor|Model $vendor
     */
    private function syncUpdatedVendorProducts(Vendor $vendor)
    {
        // get manager
        $syncUpdatedProductManager = $this->broker->getSyncUpdatedProductManager($vendor->id);

        // sync product prices
        $vendorSyncAt = $syncUpdatedProductManager->synchronizeUpdatedProducts($vendor->sync_prices_at);

        // update vendor's synchronized_at
        $vendor->sync_prices_at = $vendorSyncAt;
        $vendor->save();
    }
}
