<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Category\CreateVendorLocalCategoryRequest;
use App\Models\Category;
use App\Models\VendorCategory;
use App\Models\VendorLocalCategory;
use App\Support\Vendors\ProductManagers\Delete\UnlinkVendorLocalCategoryManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

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
     * @var UnlinkVendorLocalCategoryManager
     */
    private $unlinkVendorLocalCategoryManager;

    /**
     * VendorLocalCategoryController constructor.
     * @param VendorCategory $vendorCategory
     * @param Category $category
     * @param VendorLocalCategory $vendorLocalCategory
     * @param UnlinkVendorLocalCategoryManager $unlinkVendorLocalCategoryManager
     */
    public function __construct(VendorCategory $vendorCategory, Category $category, VendorLocalCategory $vendorLocalCategory, UnlinkVendorLocalCategoryManager $unlinkVendorLocalCategoryManager)
    {
        $this->vendorCategory = $vendorCategory;
        $this->category = $category;
        $this->vendorLocalCategory = $vendorLocalCategory;
        $this->unlinkVendorLocalCategoryManager = $unlinkVendorLocalCategoryManager;
    }

    /**
     * Show form to link local category to vendor category.
     *
     * @param int $vendorCategoryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(int $vendorCategoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

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
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

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
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('categories_id');

        if ($this->unlinkVendorLocalCategoryManager->unlinkVendorCategory($vendorCategoryId, $localCategoryId)) {
            return back();
        } else {
            return back()->withErrors([trans('validation.product_in_stock')]);
        }
    }

    /**
     * Turn on auto download products of synchronized categories.
     *
     * @return bool|RedirectResponse
     */
    public function autoDownloadOn()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

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
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

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
