<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Http\Controllers\Controller;
use App\Support\Vendors\VendorBroker;
use GuzzleHttp\Exception\RequestException;
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
     * VendorCategoryController constructor.
     * @param Vendor $vendor
     * @param VendorCategory $vendorCategory
     * @param VendorBroker $vendorBroker
     * @param Category $category
     * @param Product $product
     */
    public function __construct(Vendor $vendor, VendorCategory $vendorCategory, VendorBroker $vendorBroker, Category $category, Product $product)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendor = $vendor;
        $this->vendorBroker = $vendorBroker;
        $this->category = $category;
        $this->product = $product;
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

        $vendorSynchronizedCategories = $vendor->vendorCategory()
            ->with('localCategory')
            ->withCount('vendorProducts')
            ->get()
            ->keyBy('vendor_category_id');


        try {
            $vendorCategories = $this->vendorBroker->getVendorProvider($vendorId)->getCategories();

            return view('content.admin.vendors.category.list.index')->with(compact('vendor', 'vendorSynchronizedCategories', 'vendorCategories'));
        } catch (RequestException $e) {
            return view('content.admin.vendors.category.list.index')->with(compact('vendor'));
        }
    }

    /**
     * Show category sync form.
     *
     * @param int $vendorId
     * @param int $categoryId
     * @return View
     */
    public function sync(int $vendorId, int $categoryId): View
    {
        $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

        $categories = $this->category->defaultOrder()->withDepth()->get()->toTree();

        try {
            $vendorCategory = $this->vendorBroker->getVendorProvider($vendorId)->getCategory($categoryId);


            return view('content.admin.vendors.category.sync.index')->with(compact('vendor', 'categories', 'vendorCategory'));

        } catch (RequestException $e) {
            return view('content.admin.vendors.category.sync.index')->with(compact('vendor'));
        }

    }

    /**
     * Link vendor category with local category.
     *
     * @return RedirectResponse
     */
    public function link(): RedirectResponse
    {
        $vendorsId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_category_id');
        $localCategoryId = (int)request()->get('categories_id');

        $vendorCategoryRu = $this->vendorBroker->getVendorProvider($vendorsId)->getCategory($vendorCategoryId, 'ru');
        $vendorCategoryUa = $this->vendorBroker->getVendorProvider($vendorsId)->getCategory($vendorCategoryId, 'ua');

        $vendorCategory = $this->vendorCategory->newQuery()->create([
            'vendors_id' => $vendorsId,
            'vendor_category_id' => $vendorCategoryId,
            'name_ru' => $vendorCategoryRu->name,
            'name_ua' => $vendorCategoryUa->name,
        ]);

        $linkingCategory = $this->category->newQuery()->findOrFail($localCategoryId);


        $linkingCategory->vendor_categories_id = $vendorCategory->id;
        $linkingCategory->save();

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
        $vendorCategoryId = (int)request()->get('vendor_category_id');

        $this->vendorCategory->newQuery()->where([
            ['vendors_id', $vendorsId],
            ['vendor_category_id', $vendorCategoryId],
        ])->delete();

        return redirect(route('vendor.categories.index', ['vendorId' => $vendorsId]));
    }

    /**
     * Unlink vendor category from local category.
     * Delete vendor's products that don't have another vendor.
     *
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete()
    {
        $vendorsId = (int)request()->get('vendors_id');
        $vendorCategoryId = (int)request()->get('vendor_category_id');

        $vendorCategory = $this->vendorCategory->newQuery()
            ->where([
                ['vendors_id', $vendorsId],
                ['vendor_category_id', $vendorCategoryId],
            ])
            ->with('products')
            ->first();

        $this->product->newQuery()
            ->whereIn('id', $vendorCategory->products->pluck('id')->toArray())
            ->has('vendorProduct', '=', 1)
            ->delete();

        $vendorCategory->delete();

        return redirect(route('vendor.categories.index', ['vendorId' => $vendorsId]));
    }
}
