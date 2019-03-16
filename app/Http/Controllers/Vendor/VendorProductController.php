<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Support\Vendors\ProductManagers\Delete\UnlinkVendorProductManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class VendorProductController extends Controller
{
    /**
     * @var VendorCategory
     */
    private $vendorCategory;
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
     * @var UnlinkVendorProductManager
     */
    private $unlinkVendorProductManager;


    /**
     * VendorProductController constructor.
     * @param VendorCategory $vendorCategory
     * @param Category $category
     * @param VendorProduct $vendorProduct
     * @param UnlinkVendorProductManager $unlinkVendorProductManager
     */
    public function __construct(VendorCategory $vendorCategory, Category $category, VendorProduct $vendorProduct, UnlinkVendorProductManager $unlinkVendorProductManager)
    {
        $this->vendorCategory = $vendorCategory;
        $this->vendorProduct = $vendorProduct;
        $this->category = $category;
        $this->unlinkVendorProductManager = $unlinkVendorProductManager;
    }

    /**
     * Get already downloaded products in given vendor and local categories.
     *
     * @param Request $request
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function downloaded(Request $request, int $vendorCategoryId, int $localCategoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorCategory = $this->vendorCategory->newQuery()->with('vendor')->findOrFail($vendorCategoryId);

        $localCategory = $this->category->newQuery()->findOrFail($localCategoryId);

        $downloadedVendorProducts = $this->vendorProduct->newQuery()
            ->whereHas('vendorCategories', function ($query) use ($vendorCategoryId) {
                $query->where('id', $vendorCategoryId);
            })
            ->whereHas('product.categories', function ($query) use ($localCategoryId) {
                $query->where('id', $localCategoryId);
            })
            ->with('product.primaryImage')
            ->get();

        // calculate product profit
        foreach ($downloadedVendorProducts as $vendorProduct) {
            if ($vendorProduct->price && $vendorProduct->product->price1) {
                $vendorProduct->profit = number_format($vendorProduct->product->price1 - $vendorProduct->price, 2);
                $vendorProduct->profitPercents = number_format($vendorProduct->profit / $vendorProduct->price * 100, 2);
            } else {
                $vendorProduct->profit = null;
                $vendorProduct->profitPercents = null;
            }
        }

        // sort products
        if ($request->has('sortBy')) {
            $downloadedVendorProducts = $this->sortProducts($request, $downloadedVendorProducts);
        }

        // create paginator
        $perPage = config('admin.vendor_products_per_page');
        $page = $request->has('page') ? $request->get('page') : 1;
        $total = $downloadedVendorProducts->count();
        $pageProducts = $downloadedVendorProducts->slice($perPage * ($page - 1), $perPage);

        $downloadedVendorProducts = (new LengthAwarePaginator($pageProducts, $total, $perPage, $page))
            ->withPath(request()->getPathInfo());

        return view('content.admin.vendors.products.downloaded.index')->with(compact('vendorCategory', 'localCategory', 'downloadedVendorProducts'));
    }

    /**
     * Delete synchronized vendor product and product if allowed.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function delete()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $vendorProductId = request()->get('vendor_product_id');
        $productId = request()->get('product_id');
        $vendorCategoryId = (int)request()->get('vendor_categories_id');
        $localCategoryId = (int)request()->get('categories_id');

        if ($this->unlinkVendorProductManager->unlinkSingleVendorProduct($vendorProductId, $productId, $vendorCategoryId,  $localCategoryId)){
            return back();
        }else{
            return back()->withErrors([trans('validation.product_in_stock')]);
        }
    }



    /**
     * Add sort by condition.
     *
     * @param Request $request
     * @param Collection $vendorProducts
     * @return Collection
     */
    private function sortProducts(Request $request, Collection $vendorProducts): Collection
    {
        switch ($request->get('sortBy')) {
            case 'createdAt' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('vendor_created_at');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('vendor_created_at');
                }
                break;

            case 'downloadedAt' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('created_at');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('created_at');
                }
                break;

            case 'published' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortByDesc('product.published');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortBy('product.published');
                }
                break;

            case 'archived' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortByDesc('is_archive');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortBy('is_archive');
                }
                break;

            case 'name' :
                $locale = app()->getLocale();

                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('product.name_' . $locale);
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('product.name_' . $locale);
                }
                break;

            case 'country' :
                $locale = app()->getLocale();

                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('product.manufacturer_' . $locale);
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('product.manufacturer_' . $locale);
                }
                break;

            case 'warranty' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('warranty');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('warranty');
                }
                break;

            case 'price' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('price');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('price');
                }
                break;

            case 'profitSum' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('profit');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('profit');
                }
                break;

            case 'profitPercents' :
                if ($request->get('sortMethod') === 'asc') {
                    return $vendorProducts->sortBy('profitPercents');
                } else if ($request->get('sortMethod') === 'desc') {
                    return $vendorProducts->sortByDesc('profitPercents');
                }
                break;
        }

        return $vendorProducts;
    }
}
