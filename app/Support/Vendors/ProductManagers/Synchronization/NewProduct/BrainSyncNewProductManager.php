<?php
/**
 * Retrieve Brain new products data for sync.
 */

namespace App\Support\Vendors\ProductManagers\Synchronization\NewProduct;


use App\Contracts\Vendor\SyncTypeInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Jobs\Vendors\InsertVendorProduct;
use App\Models\SynchronizingProduct;
use App\Models\VendorCategory;
use App\Support\Vendors\Providers\BrainSyncNewProductProvider;
use Illuminate\Bus\Dispatcher;
use Exception;
use Illuminate\Support\Collection;

class BrainSyncNewProductManager extends SyncNewProductManager
{
    /**
     * @var BrainSyncNewProductProvider
     */
    private $provider;
    /**
     * @var VendorCategory
     */
    private $vendorCategory;

    /**
     * BrainSyncProductPriceManager constructor.
     * @param BrainSyncNewProductProvider $provider
     * @param VendorCategory $vendorCategory
     * @param SynchronizingProduct $synchronizingProduct
     */
    public function __construct(BrainSyncNewProductProvider $provider, VendorCategory $vendorCategory, SynchronizingProduct $synchronizingProduct)
    {
        parent::__construct($synchronizingProduct);

        $this->provider = $provider;
        $this->vendorCategory = $vendorCategory;
    }

    /**
     * Retrieve price modified products data from vendor.
     *
     * @param string|null $lastSynchronizedAt
     * @return string
     * @throws Exception
     */
    protected function getSyncProductsData(string $lastSynchronizedAt):string
    {
            // retrieve new products ids from vendor
            $vendorNewProductsIds = $this->provider->getNewProductsIds($lastSynchronizedAt);

            // get already queued vendor products ids
            $queuedVendorProductsIds = $this->getQueuedVendorProductsIds($vendorNewProductsIds);

            // processing vendor products ids
            $processingVendorProductsIds = array_diff($vendorNewProductsIds, $queuedVendorProductsIds);

            // prepare data for create jobs
            if ($processingVendorProductsIds) {
                $this->syncProductsData = $this->createProductsDataToSync($processingVendorProductsIds);
            }

            return $this->provider->getVendorSynchronizedAt();
    }

    /**
     * Create and dispatch jos
     *
     * @param array $vendorCategories
     * @param array $localCategories
     * @param int $vendorProductId
     * @return int
     */
    protected function dispatchJob(array $vendorCategories, array $localCategories, int $vendorProductId): int
    {
        // create job
        $job = new InsertVendorProduct(VendorInterface::BRAIN, $vendorCategories, $localCategories, $vendorProductId);

        $job->onConnection('database')
            ->onQueue(SyncTypeInterface::INSERT_PRODUCT);

        // dispatch job
        $jobId = app(Dispatcher::class)->dispatch($job);

        return $jobId;
    }

    /**
     * Insert product in synchronizing products
     *
     * @param int $jobId
     * @param array $vendorCategories
     * @param array $localCategories
     * @param int $vendorProductId
     */
    protected function insertSynchronizingProducts(int $jobId, array $vendorCategories, array $localCategories, int $vendorProductId)
    {
        // insert for each vendor category
        foreach ($vendorCategories as $vendorCategory) {
            // insert for each local category
            foreach ($localCategories as $localCategory) {
                // create model
                $this->synchronizingProduct->newQuery()->create([
                    'jobs_id' => $jobId,
                    'vendor_product_id' => $vendorProductId,
                    'vendors_id' => VendorInterface::BRAIN,
                    'sync_type' => SyncTypeInterface::INSERT_PRODUCT,
                    'vendor_categories_id' => $vendorCategory,
                    'categories_id' => $localCategory,
                ]);
            }
        }
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
                ['vendors_id', '=', VendorInterface::BRAIN],
                ['sync_type', '=', SyncTypeInterface::INSERT_PRODUCT],
            ])
            ->whereIn('vendor_product_id', $vendorNewProductsIds)
            ->select('vendor_product_id')
            ->distinct()
            ->get()
            ->pluck('vendor_product_id')
            ->toArray();
    }

    /**
     * Get vendor products ids that presents in DB and needing to price sync.
     *
     * @param $processingVendorProductsIds
     * @return array
     * @throws Exception
     */
    private function createProductsDataToSync(array $processingVendorProductsIds): array
    {
        // collect sync data
        $syncVendorProductsData = [];

        // get array of new vendor categories ids keyed by vendor products ids
        $vendorCategoriesIdsByProductsIds = $this->getVendorCategoriesKeyedByProducts($processingVendorProductsIds);

        // processing products vendor categories ids
        $processingProductsVendorCategoriesId = array_values($vendorCategoriesIdsByProductsIds);

        // available to auto add products vendor categories with local categories
        $availableVendorCategories = $this->getAvailableSynchronizedVendorCategories($processingProductsVendorCategoriesId);

        // available to auto add products vendor categories ids
        $availableVendorCategoriesIds = $availableVendorCategories->keys()->all();

        foreach ($vendorCategoriesIdsByProductsIds as $vendorProductId => $vendorCategoryId) {

            // processing product category is in possible categories
            if (in_array($vendorCategoryId, $availableVendorCategoriesIds)) {

                // vendor category of current vendor product's id
                $vendorCategory = $availableVendorCategories->get($vendorCategoryId);

                // collect data for create jobs
                $syncVendorProductsData[$vendorProductId] = [
                    'vendor_categories' => [$vendorCategory->id],
                    'local_categories' => $vendorCategory->autoAddNewProductsCategories->pluck('id')->toArray(),
                ];
            }
        }

        return $syncVendorProductsData;
    }

    /**
     * Create array of new vendor categories ids keyed by vendor products ids.
     *
     * @param array $newVendorProductsIds
     * @return array
     * @throws Exception
     */
    private function getVendorCategoriesKeyedByProducts(array $newVendorProductsIds): array
    {
        $productsData = $this->provider->getProductsContent($newVendorProductsIds);

        return collect($productsData)->pluck('categoryID', 'productID')->toArray();
    }

    /**
     * Get available to auto add products vendor categories with local categories.
     *
     * @param array $processingVendorCategoriesId
     * @return Collection
     */
    private function getAvailableSynchronizedVendorCategories(array $processingVendorCategoriesId): Collection
    {
        return $this->vendorCategory->newQuery()
            ->where('vendors_id', VendorInterface::BRAIN)
            ->whereIn('vendor_category_id', $processingVendorCategoriesId)
            ->has('autoAddNewProductsCategories')
            ->with('autoAddNewProductsCategories')
            ->get()
            ->keyBy('vendor_category_id');
    }
}
