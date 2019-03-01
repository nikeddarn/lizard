<?php

namespace App\Http\Controllers\Shop;

use App\Support\Shop\Products\SearchProducts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function index(Request $request)
    {
        if (!$request->has('search_for')) {
            abort(404);
        }

        $locale = app()->getLocale();

        $searchingText = $request->get('search_for');

        $foundProductsIds = $this->searchProducts->getFoundProductsIds($searchingText);

        return redirect(route('shop.search.results', ['locale' => $locale]))->with(compact('searchingText', 'foundProductsIds'));
    }

    /**
     * Show found products.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function results(Request $request)
    {
        $request->session()->keep(['searchingText', 'foundProductsIds']);

        $searchingText = $request->session()->get('searchingText');

        $foundProductsIds = $request->session()->get('foundProductsIds', []);

        $products = $this->searchProducts->getProducts($foundProductsIds);

        return view('content.shop.search.index')->with(compact('searchingText', 'products'));
    }
}
