<?php

namespace App\Http\Controllers\Export\Hotline;

use App\Contracts\Dealer\DealerInterface;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\Product\ProductProfit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class HotlineProductController extends Controller
{
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductProfit
     */
    private $productProfit;
    /**
     * @var Category
     */
    private $category;

    /**
     * HotlineCategoryController constructor.
     * @param Category $category
     * @param Product $product
     * @param ProductProfit $productProfit
     */
    public function __construct(Category $category, Product $product, ProductProfit $productProfit)
    {
        $this->product = $product;
        $this->productProfit = $productProfit;
        $this->category = $category;
    }

    /**
     * @param int $categoryId
     * @return View
     */
    public function index(int $categoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $category = $this->category->newQuery()->findOrFail($categoryId);

        $query = $this->product->newQuery()
            ->where('is_archive', '=', 0)
            ->whereHas('categoryProducts', function ($query) use ($categoryId) {
                $query->where('categories_id', $categoryId);
            })
            ->with(['dealerProduct' => function ($query) {
                $query->where('dealers_id', DealerInterface::HOTLINE);
            }])
            ->with('primaryImage', 'vendorProducts');

        if (request()->has('sortBy')) {
            $query = $this->addSortByConstraint($query);
        }

        $products = $query->paginate(config('admin.show_items_per_page'))->appends(request()->query());

        $this->setProductParameters($products);

        return view('content.admin.export.hotline.products.list.index')->with(compact('category', 'products'));
    }

    /**
     * @param int $categoryId
     * @return View
     */
    public function search(int $categoryId)
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $searchText = request()->get('search_for');

        $category = $this->category->newQuery()->findOrFail($categoryId);

        $products = $this->product->newQuery()
            ->where('is_archive', '=', 0)
            ->whereHas('categoryProducts', function ($query) use ($categoryId) {
                $query->where('categories_id', $categoryId);
            })
            ->where('name_ru', 'LIKE', '%' . $searchText . '%')
            ->with(['dealerProduct' => function ($query) {
                $query->where('dealers_id', DealerInterface::HOTLINE);
            }])
            ->with('primaryImage', 'vendorProducts')
            ->get();

        $this->setProductParameters($products);

        return view('content.admin.export.hotline.products.search.index')->with(compact('category', 'products', 'searchText'));
    }

    /**
     * Set product published.
     *
     * @return bool|RedirectResponse
     */
    public function publish()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $productId = (int)request()->get('product_id');

        $product = $this->product->newQuery()->findOrFail($productId);

        $product->dealers()->syncWithoutDetaching([
            DealerInterface::HOTLINE => [
                'published' => 1,
            ]
        ]);

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Set product un published.
     *
     * @return bool|RedirectResponse
     */
    public function unPublish()
    {
        if (Gate::denies('vendor-catalog', auth('web')->user())) {
            abort(401);
        }

        $productId = (int)request()->get('product_id');

        $product = $this->product->newQuery()->findOrFail($productId);

        $product->dealers()->syncWithoutDetaching([
            DealerInterface::HOTLINE => [
                'published' => 0,
            ]
        ]);

        if (request()->ajax()) {
            return 'true';
        } else {
            return back();
        }
    }

    /**
     * Add sort by condition.
     *
     * @param Request $request
     * @param Builder $query
     * @return Builder
     */
    private function addSortByConstraint(Builder $query): Builder
    {
        switch (request()->get('sortBy')) {
            case 'createdAt' :
                if (request()->get('sortMethod') === 'asc') {
                    $query->orderBy('created_at');
                } else if (request()->get('sortMethod') === 'desc') {
                    $query->orderByDesc('created_at');
                }
                break;

            case 'published' :
                if (request()->get('sortMethod') === 'asc') {
                    $query->orderByDesc('published');
                } else if (request()->get('sortMethod') === 'desc') {
                    $query->orderBy('published');
                }
                break;

            case 'name':
                $locale = app()->getLocale();

                if (request()->get('sortMethod') === 'asc') {
                    $query->orderBy('name_' . $locale);
                } else if (request()->get('sortMethod') === 'desc') {
                    $query->orderByDesc('name_' . $locale);
                }
                break;
        }

        return $query;
    }

    /**
     * @param $products
     */
    private function setProductParameters($products)
    {
        foreach ($products as $product) {
            $profitSum = $this->productProfit->getProfit($product);

            if ($profitSum) {
                $profitPercents = $profitSum / $product->price1 * 100;
            } else {
                $profitPercents = null;
            }

            $product->profitSum = number_format($profitSum, 2);
            $product->profitPercents = number_format($profitPercents, 2);
        }
    }
}
