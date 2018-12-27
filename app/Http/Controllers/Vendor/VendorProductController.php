<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Vendor\SyncTypeInterface;
use App\Http\Controllers\Controller;
use App\Jobs\Vendors\InsertVendorProduct;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Job;
use App\Models\Product;
use App\Models\SynchronizingProduct;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VendorProductController extends Controller
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
     * @var Product
     */
    private $product;
    /**
     * @var SynchronizingProduct
     */
    private $synchronizingProduct;
    /**
     * @var ProductImageHandler
     */
    private $imageHandler;


    /**
     * VendorProductController constructor.
     * @param VendorCategory $vendorCategory
     * @param Category $category
     * @param VendorBroker $vendorBroker
     * @param VendorProduct $vendorProduct
     * @param Product $product
     * @param SynchronizingProduct $synchronizingProduct
     * @param ProductImageHandler $imageHandler
     */
    public function __construct(VendorCategory $vendorCategory, Category $category, VendorBroker $vendorBroker, VendorProduct $vendorProduct, Product $product, SynchronizingProduct $synchronizingProduct, ProductImageHandler $imageHandler)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendorBroker = $vendorBroker;
        $this->vendorProduct = $vendorProduct;
        $this->category = $category;
        $this->product = $product;
        $this->synchronizingProduct = $synchronizingProduct;
        $this->imageHandler = $imageHandler;
    }

    /**
     * @param int $vendorId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return View
     */
    public function index(int $vendorId, int $vendorCategoryId, int $localCategoryId)
    {
        // get categories model for view
        $vendorCategory = $this->vendorCategory->newQuery()->where('id', $vendorCategoryId)->with('vendor')->firstOrFail();

        $localCategory = $this->category->newQuery()->findOrFail($localCategoryId);

        // define current page
        $page = request()->has('page') ? request()->get('page') : 1;

        try {
            // get paginator for current page
            $vendorProcessingProducts = $this->vendorBroker->getVendorCatalogManager($vendorId)->getCategoryPageProducts($vendorCategory->vendor_category_id, $page);

        } catch (Exception $exception) {
            return view('content.admin.vendors.category.products.index')
                ->with(compact('vendorCategory', 'localCategory'))
                ->withErrors(['message' => $exception->getMessage()]);
        }

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

        return view('content.admin.vendors.category.products.index')->with(compact('vendorCategory', 'localCategory', 'vendorProcessingProducts', 'totalSynchronizedProductsCount'));
    }


    /**
     * Insert checked products.
     * Delete unchecked products.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload()
    {
        $vendorId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('local_categories_id');

        $processingVendorProductsIds = request()->get('vendor_own_product_id');
        $checkedVendorProductsIds = request()->has('selected_vendor_own_product_id') ? request()->get('selected_vendor_own_product_id') : [];

        // get already synchronized products ids
        $synchronizedProductsIds = $this->getSynchronizedVendorProductsQuery($vendorCategoryId, $localCategoryId)
            ->whereIn('vendor_product_id', $processingVendorProductsIds)
            ->pluck('vendor_product_id')
            ->toArray();

        // get queued products ids
        $queuedVendorProductsIds = $this->getQueuedVendorProductsIds($vendorId, $vendorCategoryId, $localCategoryId, $processingVendorProductsIds);

        // insert checked vendor products
        $insertingVendorProductsIds = array_diff($checkedVendorProductsIds, $synchronizedProductsIds, $queuedVendorProductsIds);
        $this->insertVendorProducts($vendorId, $vendorCategoryId, $localCategoryId, $insertingVendorProductsIds);

        // delete unchecked vendor products
        $deletingVendorProductsIds = array_diff(array_merge($synchronizedProductsIds, $queuedVendorProductsIds), $checkedVendorProductsIds);
        $this->deleteVendorProducts($vendorId, $vendorCategoryId, $localCategoryId, $deletingVendorProductsIds);

        return back();
    }

    /**
     * Insert all products of category.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadAll()
    {
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

        // insert not inserted vendor products
        $this->insertVendorProducts($vendorId, $vendorCategoryId, $localCategoryId, $insertingVendorProductsIds);

        return back();
    }

    /**
     * Get already synchronized products from processing vendor products.
     *
     * @return string
     */
    public function uploaded()
    {
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
     */
    private function insertVendorProducts(int $vendorId, int $vendorCategoryId, int $localCategoryId, array $vendorProductsIds)
    {
        $attachingVendorCategories = [$vendorCategoryId];
        $attachingLocalCategories = [$localCategoryId];

        foreach ($vendorProductsIds as $vendorProductId) {
            // dispatch product to inserting queue
            $jobId = $this->dispatch(
                (new InsertVendorProduct($vendorId, $attachingVendorCategories, $attachingLocalCategories, $vendorProductId))
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
     * @param int $vendorId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param array $vendorProductsIds
     * @return \Illuminate\Http\RedirectResponse
     */
    private function deleteVendorProducts(int $vendorId, int $vendorCategoryId, int $localCategoryId, array $vendorProductsIds)
    {
        DB::beginTransaction();

        try {
            // detach product from local category
            CategoryProduct::query()
                ->whereHas('product.vendorProducts', function ($query) use ($vendorProductsIds) {
                    $query->whereIn('vendor_product_id', $vendorProductsIds);
                })
                ->where('categories_id', $localCategoryId)
                ->delete();

            // deleting products ids
            $deletingProductsIds = $this->product->newQuery()
                ->whereHas('vendorProducts', function ($query) use ($vendorProductsIds, $vendorId) {
                    $query->whereIn('vendor_product_id', $vendorProductsIds)
                        ->where('vendors_id', $vendorId);
                })
                ->doesntHave('categories')
                ->get()
                ->pluck('id')
                ->toArray();

            // delete products
            $this->product->newQuery()
                ->whereHas('vendorProducts', function ($query) use ($vendorProductsIds, $vendorId) {
                    $query->whereIn('vendor_product_id', $vendorProductsIds)
                        ->where('vendors_id', $vendorId);
                })
                ->doesntHave('categories')
                ->delete();


            // delete from jobs
            Job::query()->whereHas('synchronizingProduct', function ($query) use ($vendorCategoryId, $localCategoryId, $vendorProductsIds) {
                $query->where([
                    ['vendor_categories_id', '=', $vendorCategoryId],
                    ['categories_id', '=', $localCategoryId],
                ])
                    ->whereIn('vendor_product_id', $vendorProductsIds);
            })
                ->delete();

            DB::commit();

        } catch (Exception $exception) {
            DB::rollBack();

            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        // delete products images from storage
        $this->imageHandler->deleteProductsImages($deletingProductsIds);

        return back();
    }
}
