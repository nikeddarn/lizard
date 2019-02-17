<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Http\Controllers\Controller;
use App\Models\VendorLocalCategory;
use App\Models\VendorProduct;
use App\Rules\LeafCategory;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VendorCategoryController extends Controller
{
    /**
     * @var Category
     */
    private $vendorCategory;
    /**
     * @var Vendor
     */
    private $vendor;
    /**
     * @var VendorBroker
     */
    private $vendorBroker;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var VendorLocalCategory
     */
    private $vendorLocalCategory;
    /**
     * @var VendorProduct
     */
    private $vendorProduct;

    /**
     * VendorCategoryController constructor.
     * @param Vendor $vendor
     * @param VendorCategory $vendorCategory
     * @param VendorBroker $vendorBroker
     * @param Category $category
     * @param Product $product
     * @param VendorLocalCategory $vendorLocalCategory
     * @param VendorProduct $vendorProduct
     */
    public function __construct(Vendor $vendor, VendorCategory $vendorCategory, VendorBroker $vendorBroker, Category $category, Product $product, VendorLocalCategory $vendorLocalCategory, VendorProduct $vendorProduct)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendor = $vendor;
        $this->vendorBroker = $vendorBroker;
        $this->category = $category;
        $this->product = $product;
        $this->vendorLocalCategory = $vendorLocalCategory;
        $this->vendorProduct = $vendorProduct;
    }

    /**
     * Show vendor categories list.
     *
     * @param int $vendorId
     * @return View
     */
    public function index(int $vendorId): View
    {
        $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

        //get locale
        $locale = app()->getLocale();

        // get synchronized categories with products count
        $synchronizedCategories = $this->getSynchronizedCategoriesWithProductsCountQuery($locale)
            ->whereRaw("vendor_categories.vendors_id = $vendorId")
            ->get();

        try {
            // try to get categories from vendor
            $vendorCategories = $this->vendorBroker->getVendorCatalogManager($vendorId)->getVendorCategoriesTree();

        } catch (Exception $exception) {
            return view('content.admin.vendors.category.list.index')
                ->with(compact('vendor'))->withErrors([$exception->getMessage()]);
        }

        return view('content.admin.vendors.category.list.index')
            ->with(compact('vendor', 'synchronizedCategories', 'vendorCategories'));
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
        // define current page
        $page = request()->has('page') ? request()->get('page') : 1;

        try {
            // get vendor
            $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

            // get vendor category data
            $vendorCategory = $this->vendorBroker->getVendorCatalogManager($vendorId)->getVendorCategoryData($vendorCategoryId);

            // get paginator for current page
            $vendorCategoryProducts = $this->vendorBroker->getVendorCatalogManager($vendorId)->getCategoryPageProducts($vendorCategoryId, $page);

        } catch (Exception $exception) {
            return view('content.admin.vendors.category.products.index')
                ->withErrors([$exception->getMessage()]);
        }

        return view('content.admin.vendors.category.products.index')->with(compact('vendor', 'vendorCategory', 'vendorCategoryProducts'));
    }

    /**
     * Show synchronized categories.
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function synchronized()
    {
        //get locale
        $locale = app()->getLocale();

        // get synchronized categories with products count
        $synchronizedCategories = $this->getSynchronizedCategoriesWithProductsCountQuery($locale)
            ->paginate(config('admin.show_items_per_page'));

        return view('content.admin.vendors.synchronization.synchronized.index')->with(compact('synchronizedCategories'));
    }

    /**
     * Show category sync form.
     *
     * @param int $vendorId
     * @param int $vendorOwnCategoryId
     * @return View
     */
    public function sync(int $vendorId, int $vendorOwnCategoryId): View
    {
        try {

            $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

            $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

            // get vendor category data
            $vendorCategory = $this->vendorBroker->getVendorCatalogManager($vendorId)->getVendorCategoryData($vendorOwnCategoryId);

        } catch (Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }

        return view('content.admin.vendors.category.sync.index')->with(compact('vendor', 'categories', 'vendorCategory'));

    }

    /**
     * Link vendor category with local category.
     *
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function link(): RedirectResponse
    {
        $this->validate(request(), [
            'vendors_id' => ['required', 'integer'],
            'vendor_own_category_id' => ['required', 'integer'],
            'categories_id' => ['required', 'integer', new LeafCategory()],
        ]);

        $vendorsId = (int)request()->get('vendors_id');
        $vendorOwnCategoryId = (int)request()->get('vendor_own_category_id');
        $localCategoryId = (int)request()->get('categories_id');
        $autoAddNewProducts = (int)request()->has('auto_add_new_products');

        // try to get existing vendor category
        $vendorCategory = $this->vendorCategory->newQuery()->where([
            ['vendors_id', $vendorsId],
            ['vendor_category_id', $vendorOwnCategoryId],
        ])->first();

        // create new vendor category
        if (!$vendorCategory) {
            // try to get category model data
            try {
                $vendorCategoryData = $this->vendorBroker->getVendorCatalogManager($vendorsId)->getVendorCategoryModelData($vendorOwnCategoryId);
            } catch (Exception $exception) {
                return back()->withErrors([$exception->getMessage()]);
            }

            $vendorCategory = $this->vendorCategory->newQuery()->create($vendorCategoryData);
        }

        // attach vendor category to local category
        $vendorCategory->categories()->syncWithoutDetaching([
            $localCategoryId => [
                'auto_add_new_products' => $autoAddNewProducts,
            ]
        ]);

        return redirect(route('vendor.category.products.index', [
            'vendorId' => $vendorsId,
            'vendorCategoryId' => $vendorCategory->id,
            'localCategoryId' => $localCategoryId,
        ]));
    }

    /**
     * Unlink vendor category from local category.
     *
     * @return RedirectResponse
     */
    public function unlink()
    {
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('categories_id');

        $vendorId = $this->vendor->newQuery()->whereHas('vendorCategories', function ($query) use ($vendorCategoryId) {
            $query->where('id', $vendorCategoryId);
        })->firstOrFail()->id;

        // get products that synchronized with given vendor and local categories and not present and not reserved on storages
        $products = $this->product->newQuery()
            ->leftJoin('storage_products', 'storage_products.products_id', '=', 'products.id')
            ->join('vendor_products', 'vendor_products.products_id', '=', 'products.id')
            ->join('vendor_category_product', 'vendor_products.id', '=', 'vendor_category_product.vendor_products_id')
            ->join('vendor_categories', 'vendor_categories.id', '=', 'vendor_category_product.vendor_categories_id')
            ->join('category_product', 'category_product.products_id', '=', 'products.id')
            ->join('categories', 'category_product.categories_id', '=', 'categories.id')
            ->join(DB::raw('(SELECT products.id as products_id, COUNT(category_product.products_id) AS categories_count FROM products INNER JOIN category_product ON category_product.products_id = products.id GROUP BY products.id) products_categories_count'), function ($join) {
                $join->on('products.id', '=', 'products_categories_count.products_id');
            })
            ->join(DB::raw('(SELECT products.id as products_id, COUNT(vendor_products.products_id) AS vendors_count FROM products INNER JOIN vendor_products ON vendor_products.products_id = products.id GROUP BY products.id) vendor_products_count'), function ($join) {
                $join->on('products.id', '=', 'vendor_products_count.products_id');
            })
            ->where('vendor_categories.id', $vendorCategoryId)
            ->where('categories.id', $localCategoryId)
            ->selectRaw('products.*, products_categories_count.categories_count AS categories_count, vendor_products_count.vendors_count AS vendors_count, IF((storage_products.stock_quantity IS NULL OR storage_products.stock_quantity = 0) AND (storage_products.available_quantity IS NULL OR storage_products.available_quantity = 0), 0, 1) AS storage_presents')
            ->get()
            ->keyBy('id');

        // vendor category must keep linked to local category
        $keepLinked = false;

        foreach ($products as $product) {
            // skip product that presents on own storage or reserved on storage
            if ($product->storage_presents === 1) {
                $keepLinked = true;
                continue;
            }

            // detach from local category
            $product->categories()->detach($localCategoryId);

            if ($product->categories_count === 1) {
                if ($product->vendors_count === 1) {
                    // delete product
                    $product->delete();
                } else {
                    // delete vendor product
                    $this->vendorProduct->newQuery()
                        ->where('products_id', $product->id)
                        ->where('vendors_id', $vendorId)
                        ->delete();
                }
            }
        }

        // unlink vendor category
        if (!$keepLinked) {
            $this->vendorLocalCategory->newQuery()->where([
                ['vendor_categories_id', '=', $vendorCategoryId],
                ['categories_id', '=', $localCategoryId],
            ])->delete();
        }

        return $keepLinked ? back() : back()->withErrors(compact('keepLinked'));
    }

    /**
     * Turn on auto download products of synchronized categories.
     *
     * @return bool|RedirectResponse
     */
    public function autoDownloadOn()
    {
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('categories_id');

        $vendorLocalCategory = $this->vendorLocalCategory->newQuery()->where([
            ['vendor_categories_id', $vendorCategoryId],
            ['categories_id', $localCategoryId],
        ])->firstOrFail();

        $vendorLocalCategory->timestamps = false;
        $vendorLocalCategory->auto_add_new_products = 1;
        $vendorLocalCategory->save();

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Turn off auto download products of synchronized categories.
     *
     * @return bool|RedirectResponse
     */
    public function autoDownloadOff()
    {
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('categories_id');

        $vendorLocalCategory = $this->vendorLocalCategory->newQuery()->where([
            ['vendor_categories_id', $vendorCategoryId],
            ['categories_id', $localCategoryId],
        ])->firstOrFail();

        $vendorLocalCategory->timestamps = false;
        $vendorLocalCategory->auto_add_new_products = 0;
        $vendorLocalCategory->save();

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Get vendor synchronized categories products count builder.
     *
     * @param string $locale
     * @return Builder
     */
    private function getSynchronizedCategoriesWithProductsCountQuery(string $locale): Builder
    {
        return $this->vendorCategory->newQuery()
            ->selectRaw("vendor_categories.name_$locale AS vendor_category_name, categories.name_$locale AS local_category_name, categories.url AS local_category_url, COUNT(DISTINCT(products2.id)) AS products_count, SUM(IF(products2.published = 1, 1, 0)) AS published_products_count, vendor_local_categories.auto_add_new_products AS auto_add_products, vendors.name_$locale AS vendor_name, vendors.id AS vendor_id, vendor_categories.id AS vendor_category_id, categories.id AS local_category_id, vendor_categories.vendor_category_id AS own_vendor_category_id")
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
            })
            ->groupBy("vendor_categories.id", "categories.id", "vendor_local_categories.auto_add_new_products", "vendors.name_$locale", "vendor_categories.id", "categories.id", "vendors.id", "vendor_categories.vendor_category_id")
            ->orderByRaw('vendor_name, vendor_category_name');
    }
}
