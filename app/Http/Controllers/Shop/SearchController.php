<?php

namespace App\Http\Controllers\Shop;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    /**
     * @var Product
     */
    private $product;

    /**
     * SearchController constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(Request $request)
    {
        $searchingText = $request->get('search_for');
//        phpinfo();exit;
//        dd(config('scout.tntsearch'));exit;
        $products = Product::search($searchingText)->get();
        var_dump($products->pluck('name_ru')->toArray());
        echo '<br>';
        var_dump($products->count());
    }
}
