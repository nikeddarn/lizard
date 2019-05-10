<?php

namespace App\Http\Controllers\Shop;

use App\Support\Shop\Products\SearchProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * @var SearchProducts
     */
    private $searchProducts;


    /**
     * SearchController constructor.
     * @param SearchProducts $searchProducts
     */
    public function __construct(SearchProducts $searchProducts)
    {
        $this->searchProducts = $searchProducts;
    }

    /**
     * Search for products.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        if (!$request->has('search_for')) {
            abort(404);
        }

        $searchingText = $request->get('search_for');

        $foundProductsIds = $this->searchProducts->getFoundProductsIds($searchingText);

        return redirect(route('shop.search.results', ['locale' => app()->getLocale()]))->with(compact('searchingText', 'foundProductsIds'));
    }

    public function results(Request $request)
    {
        $request->session()->keep(['searchingText', 'foundProductsIds']);

        $searchingText = $request->session()->get('searchingText');

        $foundProductsIds = $request->session()->get('foundProductsIds', []);

        $productsCollection = $this->searchProducts->getProducts($foundProductsIds);

        $exactMatchProducts = $this->getExactMatchProducts($productsCollection, $searchingText);

        if ($exactMatchProducts->count()) {
            $productsCollection = $exactMatchProducts;
        }

        $page = request()->has('page') ? request()->get('page') : 1;
        $perPage = config('shop.show_products_per_page');
        $totalCount = $productsCollection->count();

        $pageProducts = $productsCollection->slice($perPage * ($page -1), $perPage);

        $products = (new LengthAwarePaginator($pageProducts, $totalCount, $perPage, $page))->appends(request()->query());

        return view('content.shop.search.index')->with(compact('searchingText', 'products'));
    }

    /**
     * Get exact match product.
     *
     * @param Collection $products
     * @param string $searchingText
     * @return Collection
     */
    private function getExactMatchProducts(Collection $products, string $searchingText): Collection
    {
        $exactMatchProducts = collect();

        foreach ($products as $product) {
            foreach ($product->toExactMatchSearchableArray() as $productProperty) {
                if ($productProperty && mb_stripos($productProperty, $searchingText) !== false) {
                    $exactMatchProducts->push($product);
                    continue 2;
                }
            }
        }

        return $exactMatchProducts;
    }
}
