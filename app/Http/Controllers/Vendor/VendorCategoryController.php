<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Requests\Vendor\Category\CreateVendorCategoryRequest;
use App\Http\Requests\Vendor\Category\UpdateVendorCategoryRequest;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Http\Controllers\Controller;
use App\Models\VendorProduct;
use App\Support\Settings\SettingsRepository;
use App\Support\Vendors\ProductManagers\Delete\UnlinkVendorCategoryManager;
use App\Support\Vendors\VendorBroker;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
     * @var VendorProduct
     */
    private $vendorProduct;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var UnlinkVendorCategoryManager
     */
    private $unlinkVendorCategoryManager;

    /**
     * VendorCategoryController constructor.
     * @param Vendor $vendor
     * @param VendorCategory $vendorCategory
     * @param VendorBroker $vendorBroker
     * @param Category $category
     * @param VendorProduct $vendorProduct
     * @param SettingsRepository $settingsRepository
     * @param UnlinkVendorCategoryManager $unlinkVendorCategoryManager
     */
    public function __construct(Vendor $vendor, VendorCategory $vendorCategory, VendorBroker $vendorBroker, Category $category, VendorProduct $vendorProduct, SettingsRepository $settingsRepository, UnlinkVendorCategoryManager $unlinkVendorCategoryManager)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendor = $vendor;
        $this->vendorBroker = $vendorBroker;
        $this->category = $category;
        $this->vendorProduct = $vendorProduct;
        $this->settingsRepository = $settingsRepository;
        $this->unlinkVendorCategoryManager = $unlinkVendorCategoryManager;
    }

    /**
     * Show list of synchronized vendor categories.
     *
     * @param int $vendorId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index(int $vendorId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

        $vendorCategories = $this->vendorCategory->newQuery()
            ->where('vendors_id', $vendorId)
            ->withCount('categories')
            ->paginate(config('admin.show_items_per_page'));

        return view('content.admin.vendors.category.list.index')->with(compact('vendor', 'vendorCategories'));
    }

    /**
     * Show synchronized vendor category.
     *
     * @param int $vendorCategoriesId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function show(int $vendorCategoriesId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorCategory = $this->vendorCategory->newQuery()
            ->with('vendor')
            ->with(['categories' => function ($query) use ($vendorCategoriesId) {
                $query->withCount(['products' => function ($query) use ($vendorCategoriesId) {
                    $query->whereHas('vendorProducts.vendorCategories', function ($query) use ($vendorCategoriesId) {
                        $query->where('id', $vendorCategoriesId);
                    });
                }]);
                $query->withCount(['products AS published_products_count' => function ($query) use ($vendorCategoriesId) {
                    $query->where('published', '1');
                    $query->whereHas('vendorProducts.vendorCategories', function ($query) use ($vendorCategoriesId) {
                        $query->where('id', $vendorCategoriesId);
                    });
                }]);
            }])
            ->findOrFail($vendorCategoriesId);

        return view('content.admin.vendors.category.show.index')->with(compact('vendorCategory'));
    }

    /**
     * Show form for download vendor category.
     *
     * @param int $vendorId
     * @param int $vendorOwnCategoryId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function create(int $vendorId, int $vendorOwnCategoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        try {
            $vendor = $this->vendor->newQuery()->findOrFail($vendorId);

            // get vendor category data
            $vendorCategory = $this->vendorBroker->getVendorCatalogManager($vendorId)->getVendorCategoryData($vendorOwnCategoryId);

            return view('content.admin.vendors.category.create.index')->with(compact('vendor', 'vendorCategory'));
        } catch (Exception $exception) {
            return view('content.admin.vendors.category.create.index')->withErrors([$exception->getMessage()]);
        }
    }

    /**
     * Store download vendor category.
     *
     * @param CreateVendorCategoryRequest $request
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateVendorCategoryRequest $request)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        try {
            $vendorsId = (int)$request->get('vendors_id');
            $vendorOwnCategoryId = (int)$request->get('vendor_own_category_id');

            $vendorCategoryConstraintsData = [
                'download_product_min_profit_sum' => $request->get('min_profit_sum_to_download_product'),
                'download_product_min_profit_percent' => $request->get('min_profit_percents_to_download_product'),
                'publish_product_min_profit_sum' => $request->get('min_profit_sum_to_publish_product'),
                'publish_product_min_profit_percent' => $request->get('min_profit_percents_to_publish_product'),
                'download_product_max_age' => $request->get('download_product_max_age'),
            ];

            // try to get category model data
            $vendorCategoryData = $this->vendorBroker->getVendorCatalogManager($vendorsId)->getVendorCategoryModelData($vendorOwnCategoryId);
        } catch (Exception $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }

        $vendorCategory = $this->vendorCategory->newQuery()->create(array_merge($vendorCategoryConstraintsData, $vendorCategoryData));

        return redirect(route('vendor.category.show', [
            'vendorCategoriesId' => $vendorCategory->id,
        ]));
    }

    /**
     * Show form for edit downloaded vendor category.
     *
     * @param int $vendorCategoryId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function edit(int $vendorCategoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorCategory = $this->vendorCategory->newQuery()->with('vendor')->findOrFail($vendorCategoryId);

        return view('content.admin.vendors.category.edit.index')->with(compact('vendorCategory'));
    }

    /**
     * Update downloaded vendor category.
     *
     * @param UpdateVendorCategoryRequest $request
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateVendorCategoryRequest $request)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorCategoryId = (int)$request->get('vendorCategoriesId');

        $vendorCategory = $this->vendorCategory->newQuery()->findOrFail($vendorCategoryId);

        $vendorCategoryConstraintsData = [
            'download_product_min_profit_sum' => $request->get('min_profit_sum_to_download_product'),
            'download_product_min_profit_percent' => $request->get('min_profit_percents_to_download_product'),
            'publish_product_min_profit_sum' => $request->get('min_profit_sum_to_publish_product'),
            'publish_product_min_profit_percent' => $request->get('min_profit_percents_to_publish_product'),
            'download_product_max_age' => $request->get('download_product_max_age'),
        ];

        // update product publish
        if ($vendorCategoryConstraintsData['publish_product_min_profit_sum'] !== $vendorCategory->publish_product_min_profit_sum || $vendorCategoryConstraintsData['publish_product_min_profit_percent'] !== $vendorCategory->publish_product_min_profit_percent) {
            $this->updateProductsPublished($vendorCategoryId, $vendorCategoryConstraintsData['publish_product_min_profit_sum'], $vendorCategoryConstraintsData['publish_product_min_profit_percent']);
        }

        $vendorCategory->update($vendorCategoryConstraintsData);

        return redirect(route('vendor.category.show', [
            'vendorCategoriesId' => $vendorCategory->id,
        ]));
    }

    /**
     * Unlink vendor category from local category.
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorCategoryId = (int)request()->get('vendorCategoriesId');
        $vendorId = (int)request()->get('vendor_id');

        if ($this->unlinkVendorCategoryManager->unlinkVendorCategory($vendorCategoryId)) {
            return redirect(route('vendor.category.list', ['vendorId' => $vendorId]));
        } else {
            return back()->withErrors([trans('validation.product_in_stock')]);
        }
    }

    /**
     * Update products `published` property.
     *
     * @param int $vendorCategoryId
     * @param float $minProfitSumToPublish
     * @param float $minProfitPercentsToPublish
     */
    private function updateProductsPublished(int $vendorCategoryId, float $minProfitSumToPublish, float $minProfitPercentsToPublish)
    {
        DB::statement('UPDATE products p, (SELECT vp.id AS vendor_product_id, vp.products_id AS product_id, MIN(vp.price) AS min_vendor_price FROM vendor_products vp GROUP BY vp.products_id) vpdata, vendor_category_product vcp SET p.published = IF((vpdata.min_vendor_price IS NULL) OR ((p.price1 - vpdata.min_vendor_price) > :mps) OR (((p.price1 - vpdata.min_vendor_price) / vpdata.min_vendor_price * 100) > :mpp), 1, 0) WHERE p.id = vpdata.product_id AND vpdata.vendor_product_id = vcp.vendor_products_id AND vcp.vendor_categories_id = :vc_id', [
            'mps' => $minProfitSumToPublish,
            'mpp' => $minProfitPercentsToPublish,
            'vc_id' => $vendorCategoryId,
        ]);
    }
}
