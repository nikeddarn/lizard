<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Jobs\Vendors\InsertVendorProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Support\Vendors\VendorBroker;
use Exception;
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
     * VendorProductController constructor.
     * @param VendorCategory $vendorCategory
     * @param Category $category
     * @param VendorBroker $vendorBroker
     * @param VendorProduct $vendorProduct
     * @param Product $product
     */
    public function __construct(VendorCategory $vendorCategory, Category $category, VendorBroker $vendorBroker, VendorProduct $vendorProduct, Product $product)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendorBroker = $vendorBroker;
        $this->vendorProduct = $vendorProduct;
        $this->category = $category;
        $this->product = $product;
    }

    /**
     * @param int $vendorId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return View
     * @throws Exception
     */
    public function index(int $vendorId, int $vendorCategoryId, int $localCategoryId)
    {
        $vendorCategory = $this->vendorCategory->newQuery()->where('id', $vendorCategoryId)->with('vendor')->firstOrFail();
        $localCategory = $this->category->newQuery()->findOrFail($localCategoryId);

        $synchronizedProductsIds = $this->vendorProduct->newQuery()
            ->whereHas('vendorCategories', function ($query) use ($vendorCategoryId) {
                $query->where('id', $vendorCategoryId);
            })
            ->whereHas('product.categories', function ($query) use ($localCategoryId) {
                $query->where('id', $localCategoryId);
            })
            ->get()->pluck('vendor_product_id')->toArray();

        try {
            $page = request()->has('page') ? request()->get('page') : 1;

            $vendorOwnProducts = $this->vendorBroker->getVendorAdapter($vendorId)->getCategoryProducts($vendorCategory->vendor_category_id, $page);

        } catch (Exception $exception) {
            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        $vendorOwnProducts->each(function ($product) use ($synchronizedProductsIds) {
            // define already synchronized products
            $product->checked = in_array($product->id, $synchronizedProductsIds);
        });

        $synchronizedProductsCount = count($synchronizedProductsIds);

        return view('content.admin.vendors.category.products.index')->with(compact('vendorCategory', 'localCategory', 'vendorOwnProducts', 'synchronizedProductsCount'));
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

        $handlingVendorProductsIds = request()->get('vendor_own_product_id');
        $checkedVendorProductsIds = request()->get('selected_vendor_own_product_id');

        if (!is_array($checkedVendorProductsIds)) {
            $checkedVendorProductsIds = [];
        }

        // get already inserted vendor products ids for current page
        $alreadyInsertedVendorProductsIds = $this->getAlreadyInsertedVendorProductsIds($vendorCategoryId, $localCategoryId, $handlingVendorProductsIds);

        // insert checked vendor products
        $insertingVendorProductsIds = array_diff($checkedVendorProductsIds, $alreadyInsertedVendorProductsIds);
        $this->insertVendorProducts($vendorId, $vendorCategoryId, $localCategoryId, $insertingVendorProductsIds);

        // delete unchecked vendor products
        $deletingVendorProductsIds = array_diff($alreadyInsertedVendorProductsIds, $checkedVendorProductsIds);
        $this->deleteVendorProducts($vendorId, $deletingVendorProductsIds);

        return back();
    }

    public function uploadAll()
    {
        $vendorId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('local_categories_id');

        $page = 1;

        do {
            try {
                // get vendor products ids for current page
                $vendorOwnProducts = $this->vendorBroker->getVendorAdapter($vendorId)->getCategoryProducts($vendorCategoryId, $page);
                $handlingVendorProductsIds = $vendorOwnProducts->pluck('id')->toArray();
            }catch (Exception $exception){
                return back()->withErrors(['message' => "Can not load data from vendor on page $page <br/>" . $exception->getMessage()]);
            }

            // get already inserted vendor products ids for current page
            $alreadyInsertedVendorProductsIds = $this->getAlreadyInsertedVendorProductsIds($vendorCategoryId, $localCategoryId, $handlingVendorProductsIds);

            // insert vendor products of current page
            $insertingVendorProductsIds = array_diff($handlingVendorProductsIds, $alreadyInsertedVendorProductsIds);
            $this->insertVendorProducts($vendorId, $vendorCategoryId, $localCategoryId, $insertingVendorProductsIds);

            $page++;

        } while ($vendorOwnProducts->hasMorePages());

        return back();
    }

    /**
     * Get already inserted vendor products IDs.
     *
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param array $insertingVendorProductsIds
     * @return array
     */
    private function getAlreadyInsertedVendorProductsIds(int $vendorCategoryId, int $localCategoryId, array $insertingVendorProductsIds)
    {
        return $this->vendorProduct->newQuery()
            ->whereHas('vendorCategories', function ($query) use ($vendorCategoryId) {
                $query->where('id', $vendorCategoryId);
            })
            ->whereHas('product.categories', function ($query) use ($localCategoryId) {
                $query->where('id', $localCategoryId);
            })
            ->whereIn('vendor_product_id', $insertingVendorProductsIds)
            ->get()->pluck('vendor_product_id')->toArray();
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
        foreach ($vendorProductsIds as $vendorProductId) {
            // create empty vendor product
            $vendorProduct = VendorProduct::query()->firstOrCreate([
                'vendors_id' => $vendorId,
                'vendor_product_id' => $vendorProductId,
            ]);

            $this->dispatch(
                (new InsertVendorProduct($vendorProduct, $vendorCategoryId, $localCategoryId))->onConnection('database')->onQueue('insert_vendor_product')
            );
        }
    }

    /**
     * Delete vendor products.
     *
     * @param int $vendorId
     * @param array $vendorProductsIds
     * @return \Illuminate\Http\RedirectResponse
     */
    private function deleteVendorProducts(int $vendorId, array $vendorProductsIds)
    {
        $this->product->newQuery()
            ->whereHas('vendorProducts', function ($query) use ($vendorId, $vendorProductsIds) {
                $query->where('vendors_id', $vendorId);
                $query->whereIn('vendor_product_id', $vendorProductsIds);
            })
            ->has('categories')
            ->delete();

        return back();
    }
}
