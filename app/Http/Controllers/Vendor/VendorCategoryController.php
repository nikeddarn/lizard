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
use Illuminate\Http\RedirectResponse;
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

        $vendorSynchronizedCategories = $vendor->vendorCategories()
            ->with(['categories' => function ($query) use ($vendorId) {
                $query->withCount(['products' => function ($query) use ($vendorId) {
                    $query->whereHas('vendorProducts.vendorCategories', function ($query) use ($vendorId) {
                        $query->where('vendors_id', $vendorId);
                    });
                }]);
            }])
            ->get()
            ->keyBy('vendor_category_id');

        try {

            $vendorCategories = $this->vendorBroker->getVendorAdapter($vendorId)->getVendorCategoriesTree();

        } catch (Exception $exception) {
            return view('content.admin.vendors.category.list.index')->with(compact('vendor'))->withErrors(['message' => $exception->getMessage()]);
        }

        return view('content.admin.vendors.category.list.index')->with(compact('vendor', 'vendorSynchronizedCategories', 'vendorCategories'));
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
        $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        try {

            $vendorCategory = $this->vendorBroker->getVendorProvider($vendorId)->getCategory($vendorOwnCategoryId);

        } catch (Exception $exception) {
            return back()->withErrors(['message' => $exception->getMessage()]);
        }

        return view('content.admin.vendors.category.sync.index')->with(compact('vendor', 'categories', 'vendorCategory'));

    }

    /**
     * Link vendor category with local category.
     *
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
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

        $vendorCategory = $this->vendorCategory->newQuery()->where([
            ['vendors_id', $vendorsId],
            ['vendor_category_id', $vendorOwnCategoryId],
        ])->first();

        if (!$vendorCategory) {

            $vendorCategoryData = $this->vendorBroker->getVendorAdapter($vendorsId)->getVendorCategoryData($vendorOwnCategoryId);

            $vendorCategory = $this->vendorCategory->newQuery()->create($vendorCategoryData);
        }

        $vendorCategory->categories()->attach($localCategoryId, ['auto_add_new_products' => $autoAddNewProducts]);

        return redirect(route('vendor.categories.index', ['vendorId' => $vendorsId]));
    }

    /**
     * Unlink vendor category from local category.
     *
     * @return RedirectResponse
     */
    public function unlink()
    {
        $vendorsId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('categories_id');

        $vendorCategory = $this->vendorCategory->newQuery()->findOrFail($vendorCategoryId);

        $vendorCategory->products()
            ->whereHas('categories', function ($query) use ($localCategoryId) {
                $query->where('id', $localCategoryId);
            })
            ->has('vendorCategories', '=', 1)
            ->delete();

        $vendorCategory->categories()->detach($localCategoryId);

        return redirect(route('vendor.categories.index', ['vendorId' => $vendorsId]));
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
}
