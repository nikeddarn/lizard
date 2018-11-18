<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Support\Vendor\VendorProductManager;
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
     * @var VendorProductManager
     */
    private $vendorProductManager;

    /**
     * VendorProductController constructor.
     * @param VendorCategory $vendorCategory
     * @param Category $category
     * @param VendorBroker $vendorBroker
     * @param VendorProduct $vendorProduct
     * @param VendorProductManager $vendorProductManager
     */
    public function __construct(VendorCategory $vendorCategory, Category $category, VendorBroker $vendorBroker, VendorProduct $vendorProduct, VendorProductManager $vendorProductManager)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendorBroker = $vendorBroker;
        $this->vendorProduct = $vendorProduct;
        $this->category = $category;
        $this->vendorProductManager = $vendorProductManager;
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

        $synchronizedVendorProducts = $this->vendorProduct->newQuery()
            ->whereHas('vendorCategory', function ($query) use ($vendorCategoryId) {
                $query->where('id', $vendorCategoryId);
            })
            ->whereHas('product.categories', function ($query) use ($localCategoryId) {
                $query->where('id', $localCategoryId);
            })
            ->get();

        $synchronizedVendorProductsCount = $synchronizedVendorProducts->count();


        try {
            $page = request()->has('page') ? request()->get('page') : 1;

            $products = $this->vendorBroker->getVendorAdapter($vendorId)->getCategoryProducts($vendorCategory->vendor_category_id, $page);

        } catch (Exception $exception) {
            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        // store current page products ids
        session()->flash('synchronizing_vendor_products_ids', $products->pluck('id')->toArray());

        $synchronizedVendorProductsIds = $synchronizedVendorProducts->pluck('vendor_product_id')->toArray();

        $products->each(function ($product) use ($synchronizedVendorProductsIds) {
            // define already synchronized products
            $product->checked = in_array($product->id, $synchronizedVendorProductsIds);
        });

        return view('content.admin.vendors.category.products.index')->with(compact('vendorCategory', 'localCategory', 'products', 'synchronizedVendorProductsCount'));


    }


    public function upload()
    {
        $vendorId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('local_categories_id');

        $synchronizingVendorProductsIds = (array)session()->pull('synchronizing_vendor_products_ids');
        $checkedVendorProductsIds = request()->get('vendor_product_id');

        if (!is_array($checkedVendorProductsIds)) {
            $checkedVendorProductsIds = [];
        }

        $synchronizedVendorProductsIds = $this->vendorProduct->newQuery()
            ->where('vendor_categories_id', $vendorCategoryId)
            ->whereIn('vendor_product_id', $synchronizingVendorProductsIds)
            ->get()->pluck('vendor_product_id')->toArray();

        $insertingVendorProductsIds = array_diff($checkedVendorProductsIds, $synchronizedVendorProductsIds);
        $deletingVendorProductsIds = array_diff($synchronizedVendorProductsIds, $checkedVendorProductsIds);

        try {
            $vendorProductManager = $this->vendorBroker->getVendorProductManager($vendorId);

            if (!empty($insertingVendorProductsIds)) {
                $vendorProductManager->insertVendorProducts($vendorCategoryId, $localCategoryId, $insertingVendorProductsIds);
            }

            if (!empty($deletingVendorProductsIds)) {
                $vendorProductManager->deleteVendorProducts($deletingVendorProductsIds);
            }

        } catch (Exception $exception) {
            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        return back();
    }

    public function uploadAll()
    {
        $vendorsId = (int)request()->get('vendors_id');


        var_dump(request()->get('vendor_product_id'));
    }
}
