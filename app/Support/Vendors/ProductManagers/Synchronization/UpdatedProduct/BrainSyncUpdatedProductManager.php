<?php
/**
 * Retrieve Brain sync products prices data.
 */

namespace App\Support\Vendors\ProductManagers\Synchronization\UpdatedProduct;


use App\Contracts\Vendor\SyncTypeInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Jobs\Vendors\UpdateVendorProduct;
use App\Models\SynchronizingProduct;
use App\Models\VendorProduct;
use Illuminate\Bus\Dispatcher;
use App\Support\Vendors\Providers\BrainSyncUpdatedProductProvider;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class BrainSyncUpdatedProductManager extends SyncUpdatedProductManager
{
    /**
     * @var BrainSyncUpdatedProductProvider
     */
    private $provider;
    /**
     * @var VendorProduct
     */
    private $vendorProduct;

    /**
     * BrainSyncProductPriceManager constructor.
     * @param BrainSyncUpdatedProductProvider $provider
     * @param VendorProduct $vendorProduct
     * @param SynchronizingProduct $synchronizingProduct
     */
    public function __construct(BrainSyncUpdatedProductProvider $provider, VendorProduct $vendorProduct, SynchronizingProduct $synchronizingProduct)
    {
        parent::__construct($synchronizingProduct);

        $this->provider = $provider;
        $this->vendorProduct = $vendorProduct;

        $this->vendorId = VendorInterface::BRAIN;
    }

    /**
     * Retrieve price modified products data from vendor.
     *
     * @param string|null $lastSynchronizedAt
     */
    protected function getSyncProductsData(string $lastSynchronizedAt = null)
    {
        try {
            $vendorModifiedProductsIds = $this->provider->getUpdatedProductsIds($lastSynchronizedAt);

            if ($vendorModifiedProductsIds) {
                $this->vendorProductsToSync = $this->getVendorProductsToSync($vendorModifiedProductsIds);
            }

            $this->synchronizedAt = $this->provider->getVendorSynchronizedAt();

        } catch (Exception $exception) {
            Log::channel('schedule')->info($exception->getMessage());
        }
    }

    /**
     * Create and dispatch jos
     *
     * @param int $vendorId
     * @param int $vendorProductId
     * @return int
     */
    protected function dispatchJob(int $vendorId, int $vendorProductId): int
    {
        // create job
        $job = new UpdateVendorProduct($vendorId, $vendorProductId);

        $job->onConnection('database')
            ->onQueue(SyncTypeInterface::UPDATE_PRODUCT);

        // dispatch job
        $jobId = app(Dispatcher::class)->dispatch($job);

        return $jobId;
    }

    /**
     * Get vendor products ids that presents in DB and needing to price sync.
     *
     * @param array $vendorModifiedProductsIds
     * @return Collection
     */
    private function getVendorProductsToSync(array $vendorModifiedProductsIds): Collection
    {
        // queued products ids
        $queuedVendorProductsIds = $this->getQueuedVendorProductsIds($vendorModifiedProductsIds);

        // synchronized price modified not queued vendor products
        $synchronizedModifiedNotQueuedProducts = $this->getSynchronizedVendorProductsIds($vendorModifiedProductsIds, $queuedVendorProductsIds);

        return $synchronizedModifiedNotQueuedProducts;
    }

    /**
     * @param array $vendorModifiedProductsIds
     * @param array $queuedVendorProductsIds
     * @return \Illuminate\Support\Collection
     */
    private function getSynchronizedVendorProductsIds(array $vendorModifiedProductsIds, array $queuedVendorProductsIds): Collection
    {
        return $this->vendorProduct->newQuery()
            ->where('vendors_id', $this->vendorId)
            ->whereIn('vendor_product_id', $vendorModifiedProductsIds)
            ->whereNotIn('vendor_product_id', $queuedVendorProductsIds)
            ->with('vendorCategories', 'product.categories')
            ->get();
    }


    /**
     * Get already queued vendor products ids.
     *
     * @param array $vendorNewProductsIds
     * @return array
     */
    private function getQueuedVendorProductsIds(array $vendorNewProductsIds): array
    {
        return $this->synchronizingProduct->newQuery()
            ->where([
                ['vendors_id', '=', $this->vendorId],
                ['sync_type', '=', SyncTypeInterface::UPDATE_PRODUCT],
            ])
            ->whereIn('vendor_product_id', $vendorNewProductsIds)
            ->select('vendor_product_id')
            ->distinct()
            ->get()
            ->pluck('vendor_product_id')
            ->toArray();
    }
}
