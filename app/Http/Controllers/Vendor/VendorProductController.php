<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorCategory;
use App\Support\Vendors\VendorBroker;
use GuzzleHttp\Exception\RequestException;
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
     * VendorProductController constructor.
     * @param VendorCategory $vendorCategory
     * @param VendorBroker $vendorBroker
     */
    public function __construct(VendorCategory $vendorCategory, VendorBroker $vendorBroker)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendorBroker = $vendorBroker;
    }

    /**
     * @param int $vendorId
     * @param int $categoryId
     * @return View
     */
    public function index(int $vendorId, int $categoryId): View
    {
        $vendorCategory = $this->vendorCategory->newQuery()
            ->where([
                ['vendors_id', $vendorId],
                ['vendor_category_id', $categoryId],
            ])->with('vendor', 'vendorProducts', 'localCategory')
            ->first();

        $synchronizedProductsIds = $vendorCategory->vendorProducts->pluck('vendor_product_id')->toArray();

        $productsPerPage = config('admin.vendor_products_per_page');

        $page = request()->has('page') ? request()->get('page') : 1;

        try {
            $products = $this->vendorBroker->getVendorProvider($vendorId)->getProducts($categoryId, $productsPerPage, $page);

            // remove archive products
            if (!config('admin.archive_products.sync')) {
                $products->filter(function ($product) {
                    return !$product->is_archive;
                });
            }

            session()->put('synchronizedProductsIds', $synchronizedProductsIds);

            return view('content.admin.vendors.category.products.index')->with(compact('vendorCategory', 'products', 'synchronizedProductsIds'));

        } catch (RequestException $e) {
            return view('content.admin.vendors.category.products.index')->with(compact('vendorCategory'));
        }
    }

    public function upload()
    {
        $vendorsId = (int)request()->get('vendors_id');

        $oldSynchronizedProductsIds = session()->pull('synchronizedProductsIds');
        $newSynchronizedProductsIds = request()->get('vendor_product_id');



            var_dump(request()->get('vendor_product_id'));
    }

    public function uploadAll()
    {
        $vendorsId = (int)request()->get('vendors_id');



        var_dump(request()->get('vendor_product_id'));
    }
}
