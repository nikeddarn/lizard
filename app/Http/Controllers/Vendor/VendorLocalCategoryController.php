<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Category\CreateVendorLocalCategoryRequest;
use App\Models\Category;
use App\Models\VendorCategory;
use App\Models\VendorLocalCategory;
use App\Support\Settings\SettingsRepository;
use App\Support\Vendors\ProductManagers\Delete\DeleteVendorProductManager;
use Illuminate\Http\RedirectResponse;

class VendorLocalCategoryController extends Controller
{
    /**
     * @var VendorCategory
     */
    private $vendorCategory;
    /**
     * @var Category
     */
    private $category;
    /**
     * @var VendorLocalCategory
     */
    private $vendorLocalCategory;
    /**
     * @var DeleteVendorProductManager
     */
    private $deleteVendorProductManager;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * VendorLocalCategoryController constructor.
     * @param VendorCategory $vendorCategory
     * @param Category $category
     * @param VendorLocalCategory $vendorLocalCategory
     * @param DeleteVendorProductManager $deleteVendorProductManager
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(VendorCategory $vendorCategory, Category $category, VendorLocalCategory $vendorLocalCategory, DeleteVendorProductManager $deleteVendorProductManager, SettingsRepository $settingsRepository)
    {
        $this->vendorCategory = $vendorCategory;
        $this->category = $category;
        $this->vendorLocalCategory = $vendorLocalCategory;
        $this->deleteVendorProductManager = $deleteVendorProductManager;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Show form to link local category to vendor category.
     *
     * @param int $vendorCategoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(int $vendorCategoryId)
    {
        $vendorCategory = $this->vendorCategory->newQuery()->with('categories', 'vendor')->findOrFail($vendorCategoryId);

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        return view('content.admin.vendors.category.local.index')->with(compact('vendorCategory', 'categories'));
    }

    /**
     * Link local category to vendor category.
     *
     * @param CreateVendorLocalCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateVendorLocalCategoryRequest $request)
    {
        $vendorCategoryId = (int)$request->get('vendor_category_id');
        $localCategoryId = (int)$request->get('categories_id');
        $autoAddNewProducts = (int)$request->has('auto_add_new_products');

        $this->vendorLocalCategory->newQuery()->create([
            'vendor_categories_id' => $vendorCategoryId,
            'categories_id' => $localCategoryId,
            'auto_add_new_products' => $autoAddNewProducts,
        ]);

        return redirect(route('vendor.category.show', ['vendorCategoriesId' => $vendorCategoryId]));
    }

    /**
     * Delete products present in given categories. Unlink local category from vendor category.
     *
     * @throws \Exception
     */
    public function delete()
    {
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('categories_id');

        //get settings
        $deleteProductSettings = $this->settingsRepository->getProperty('vendor.delete_product');

        $localCategory = $this->category->newQuery()->findOrFail($localCategoryId);

        // get deleting vendor products
        $vendorProducts = $this->vendorProduct->newQuery()
            ->whereHas('vendorCategories', function ($query) use ($vendorCategoryId) {
                $query->where('id', $vendorCategoryId);
            })
            ->whereHas('product.categories', function ($query) use ($localCategoryId) {
                $query->where('id', $localCategoryId);
            })
            ->with(['product' => function ($query) use ($localCategoryId) {
                $query->with('categories', 'vendorProducts', 'storages', 'stockStorages');
            }])
            ->get();

        if ($deleteProductSettings['delete_product_on_delete_vendor_category']) {
            // delete vendor products anf products
            $allProductsUnlinked = $this->deleteVendorProductManager->deleteVendorProducts($vendorProducts, $localCategoryId);

            if ($allProductsUnlinked) {
                // unlink local category from vendor
                $localCategory->vendorCategories()->detach($vendorCategoryId);

                // delete local category if empty
                if ($deleteProductSettings['delete_empty_local_category_on_delete_vendor_category']) {
                    $localCategoryProductsCount = $localCategory->products()->count();

                    if (!$localCategoryProductsCount) {
                        $localCategory->delete();
                    }
                }
            }

            return $allProductsUnlinked ? back() : back()->withErrors([trans('validation.product_in_stock')]);

        } else {
            // delete only vendor products
            foreach ($vendorProducts as $vendorProduct) {
                $vendorProduct->delete();
            }

            // unlink local category from vendor
            $localCategory->vendorCategories()->detach($vendorCategoryId);

            return back();
        }
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
