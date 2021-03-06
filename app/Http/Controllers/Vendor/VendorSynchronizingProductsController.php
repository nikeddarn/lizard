<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Vendor\SyncTypeInterface;
use App\Http\Controllers\Controller;
use App\Jobs\Vendors\InsertVendorProduct;
use App\Models\Category;
use App\Models\Job;
use App\Models\SynchronizingProduct;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Support\Vendors\ProductManagers\Delete\DeleteVendorProductsManager;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class VendorSynchronizingProductsController extends Controller
{
    /**
     * @var VendorCategory
     */
    private $vendorCategory;
    /**
     * @var VendorBroker
     */
    private $vendorBroker;
    /**
     * @var VendorProduct
     */
    private $vendorProduct;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var SynchronizingProduct
     */
    private $synchronizingProduct;
    /**
     * @var DeleteVendorProductsManager
     */
    private $deleteVendorProductsManager;


    /**
     * VendorProductController constructor.
     * @param VendorCategory $vendorCategory
     * @param Category $category
     * @param VendorBroker $vendorBroker
     * @param VendorProduct $vendorProduct
     * @param SynchronizingProduct $synchronizingProduct
     * @param DeleteVendorProductsManager $deleteVendorProductsManager
     */
    public function __construct(VendorCategory $vendorCategory, Category $category, VendorBroker $vendorBroker, VendorProduct $vendorProduct, SynchronizingProduct $synchronizingProduct, DeleteVendorProductsManager $deleteVendorProductsManager)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendorBroker = $vendorBroker;
        $this->vendorProduct = $vendorProduct;
        $this->category = $category;
        $this->synchronizingProduct = $synchronizingProduct;
        $this->deleteVendorProductsManager = $deleteVendorProductsManager;
    }

    /**
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return View
     */
    public function sync(int $vendorCategoryId, int $localCategoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        try {
            // get categories model for view
            $vendorCategory = $this->vendorCategory->newQuery()->where('id', $vendorCategoryId)->with('vendor')->firstOrFail();

            $vendorId = $vendorCategory->vendor->id;

            $localCategory = $this->category->newQuery()->findOrFail($localCategoryId);

            // define current page
            $page = request()->has('page') ? request()->get('page') : 1;

            // get paginator for current page
            $vendorProcessingProducts = $this->vendorBroker->getVendorCatalogManager($vendorId)->getCategoryPageProducts($vendorCategory->vendor_category_id, $page);

            // get processing products
            $processingProductsIds = $vendorProcessingProducts->pluck('id')->toArray();

            // get all synchronized products
            $synchronizedProducts = $this->getSynchronizedVendorProductsQuery($vendorCategoryId, $localCategoryId)->get();

            // synchronized products ids for current page
            $currentPageSynchronizedProductsIds = $synchronizedProducts->whereIn('vendor_product_id', $processingProductsIds)
                ->pluck('vendor_product_id')
                ->toArray();

            // get queued products
            $queuedProductsIds = $this->getQueuedVendorProductsIds($vendorId, $vendorCategoryId, $localCategoryId, $processingProductsIds);

            // park vendor products as checked and queued
            $vendorProcessingProducts->each(function ($product) use ($currentPageSynchronizedProductsIds, $queuedProductsIds) {
                $product->checked = in_array($product->id, $currentPageSynchronizedProductsIds);
                $product->queued = in_array($product->id, $queuedProductsIds);
            });

            $totalSynchronizedProductsCount = count($synchronizedProducts);

            return view('content.admin.vendors.products.synchronization.index')->with(compact('vendorCategory', 'localCategory', 'vendorProcessingProducts', 'totalSynchronizedProductsCount'));

        } catch (Exception $exception) {
            return view('content.admin.vendors.products.synchronization.index')
                ->with(compact('vendorCategory', 'localCategory'))
                ->withErrors([$exception->getMessage()]);
        }
    }


    /**
     * Insert checked products.
     * Delete unchecked products.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadSelected()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('local_categories_id');

        $processingVendorProductsIds = request()->get('vendor_own_product_id');
        $checkedVendorProductsIds = request()->has('selected_vendor_own_product_id') ? request()->get('selected_vendor_own_product_id') : [];

        try {
            // get already synchronized products ids
            $synchronizedProductsIds = $this->getSynchronizedVendorProductsQuery($vendorCategoryId, $localCategoryId)
                ->whereIn('vendor_product_id', $processingVendorProductsIds)
                ->pluck('vendor_product_id')
                ->toArray();

            // get queued products ids
            $queuedVendorProductsIds = $this->getQueuedVendorProductsIds($vendorId, $vendorCategoryId, $localCategoryId, $processingVendorProductsIds);

            // insert checked vendor products
            $insertingVendorProductsIds = array_diff($checkedVendorProductsIds, $synchronizedProductsIds, $queuedVendorProductsIds);
            if ($insertingVendorProductsIds) {
                $this->insertVendorProducts($vendorId, $vendorCategoryId, $localCategoryId, $insertingVendorProductsIds);
            }

            // delete unchecked vendor products
            $deletingVendorProductsIds = array_diff(array_merge($synchronizedProductsIds, $queuedVendorProductsIds), $checkedVendorProductsIds);
            if ($deletingVendorProductsIds) {
                if (!$this->deleteVendorProducts($vendorCategoryId, $localCategoryId, $deletingVendorProductsIds)) {
                    return back()->withErrors([trans('validation.product_in_stock')]);
                }
            }

            return back();

        } catch (Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    /**
     * Insert all products of category.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadAll()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('local_categories_id');
        $vendorOwnCategoryId = (int)request()->get('vendor_own_category_id');

        try {
            // get all category's products ids
            $processingVendorProductsIds = $this->vendorBroker->getVendorCatalogManager($vendorId)->getAllProductsOfCategory($vendorOwnCategoryId);

        } catch (Exception $exception) {
            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        // get already synchronized products ids
        $synchronizedProductsIds = $this->getSynchronizedVendorProductsQuery($vendorCategoryId, $localCategoryId)
            ->whereIn('vendor_product_id', $processingVendorProductsIds)
            ->pluck('vendor_product_id')
            ->toArray();

        // get queued products ids
        $queuedProductsIds = $this->getQueuedVendorProductsIds($vendorId, $vendorCategoryId, $localCategoryId, $processingVendorProductsIds);

        // define not inserted vendor products
        $insertingVendorProductsIds = array_diff($processingVendorProductsIds, $synchronizedProductsIds, $queuedProductsIds);

        // insert vendor products with checking inserting conditions
        $this->insertVendorProducts($vendorId, $vendorCategoryId, $localCategoryId, $insertingVendorProductsIds, true);

        return back();
    }

    /**
     * Get already synchronized products ids from processing vendor products.
     *
     * @return string
     */
    public function downloadedIds()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        // this route for ajax only
        if (!request()->ajax()) {
            return abort(403);
        }

        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('local_categories_id');
        $processingVendorProductsIds = json_decode(request()->get('processing_vendor_products'));

        // get already synchronized products ids
        $synchronizedProductsIds = $this->getSynchronizedVendorProductsQuery($vendorCategoryId, $localCategoryId)
            ->whereIn('vendor_product_id', $processingVendorProductsIds)
            ->pluck('vendor_product_id')
            ->toArray();

        return json_encode($synchronizedProductsIds);
    }

    /**
     * Get already inserted vendor products IDs for given vendor and local categories.
     *
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return Builder
     */
    private function getSynchronizedVendorProductsQuery(int $vendorCategoryId, int $localCategoryId): Builder
    {
        return $this->vendorProduct->newQuery()
            ->whereHas('vendorCategories', function ($query) use ($vendorCategoryId) {
                $query->where('id', $vendorCategoryId);
            })
            ->whereHas('product.categories', function ($query) use ($localCategoryId) {
                $query->where('id', $localCategoryId);
            });
    }

    /**
     * Get queued vendor products IDs.
     *
     * @param int $vendorId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param array $processingProductsIds
     * @return array
     */
    private function getQueuedVendorProductsIds(int $vendorId, int $vendorCategoryId, int $localCategoryId, array $processingProductsIds)
    {
        return $this->synchronizingProduct->newQuery()
            ->where([
                ['vendor_categories_id', '=', $vendorCategoryId],
                ['categories_id', '=', $localCategoryId],
                ['vendors_id', '=', $vendorId],
                ['sync_type', '=', SyncTypeInterface::INSERT_PRODUCT],
            ])
            ->whereIn('vendor_product_id', $processingProductsIds)
            ->get()
            ->pluck('vendor_product_id')
            ->toArray();
    }


    /**
     * Insert vendor products.
     *
     * @param int $vendorId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param array $vendorProductsIds
     * @param bool $checkInsertAllow
     */
    private function insertVendorProducts(int $vendorId, int $vendorCategoryId, int $localCategoryId, array $vendorProductsIds, $checkInsertAllow = false)
    {
        $attachingVendorCategories = [$vendorCategoryId];
        $attachingLocalCategories = [$localCategoryId];

        foreach ($vendorProductsIds as $vendorProductId) {
            // dispatch product to inserting queue
            $jobId = $this->dispatch(
                (new InsertVendorProduct($vendorId, $attachingVendorCategories, $attachingLocalCategories, $vendorProductId, $checkInsertAllow))
                    ->onConnection('database')
                    ->onQueue(SyncTypeInterface::INSERT_PRODUCT)
            );

            // insert in synchronized products
            SynchronizingProduct::query()->create([
                'jobs_id' => $jobId,
                'vendors_id' => $vendorId,
                'vendor_product_id' => $vendorProductId,
                'sync_type' => SyncTypeInterface::INSERT_PRODUCT,
                'vendor_categories_id' => $vendorCategoryId,
                'categories_id' => $localCategoryId,
            ]);
        }
    }

    /**
     * Delete products and vendor products.
     *
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param array $vendorOwnProductsIds
     * @return bool
     */
    private function deleteVendorProducts(int $vendorCategoryId, int $localCategoryId, array $vendorOwnProductsIds): bool
    {
        DB::beginTransaction();

        // delete vendor products
        $isAllProductsUnlinked = $this->deleteVendorProductsManager->unlinkVendorProducts($vendorOwnProductsIds, $vendorCategoryId, $localCategoryId);

        // delete from jobs
        Job::query()->whereHas('synchronizingProduct', function ($query) use ($vendorCategoryId, $localCategoryId, $vendorOwnProductsIds) {
            $query->where([
                ['vendor_categories_id', '=', $vendorCategoryId],
                ['categories_id', '=', $localCategoryId],
            ])
                ->whereIn('vendor_product_id', $vendorOwnProductsIds);
        })
            ->delete();

        DB::commit();

        return $isAllProductsUnlinked;
    }
}
