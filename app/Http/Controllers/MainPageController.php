<?php

namespace App\Http\Controllers;

use App\Models\VendorProduct;
use App\Support\Vendors\Providers\BrainInsertProductProvider;
use Illuminate\Http\Request;
use stdClass;

class MainPageController extends Controller
{
    public function show(Request $request)
    {
//        var_dump(request()->server('HTTP_REFERER'));exit;
//        var_dump(parse_url(request()->server('HTTP_REFERER'), PHP_URL_HOST));exit;
//        var_dump($request->getRequestUri());exit;
//        var_dump(in_array(false, ['dd', 'll']));exit;
//        var_dump(route(request()->route()->getName(), array_merge(request()->query(), ['lang' => 'ru'])));exit;
//        var_dump(route(request()->route()->getName(), request()->query()));exit;
//        var_dump(route(request()->route()->getName(), array_diff(request()->query(), ['lang' => 'ru'])));exit;
//        var_dump(array_merge(request()->query(), ['lang' => 'ru']));exit;
//        var_dump((array)new stdClass());exit;
//
//        $provider = app()->make(BrainInsertProductProvider::class);
//        foreach (VendorProduct::query()
////                     ->orderByDesc('id')
//                     ->limit(2)->offset(100)->get()->pluck('vendor_product_id')->toArray() as $prid) {
//            $product = $provider->getProductData($prid)['product_data_ru'];
//            var_dump($product->name);
//            echo '<br>';
//            echo '<br>';
//            var_dump($product->stocks);
//            echo '<br>';
//            echo '<br>';
//            var_dump($product->available);
//            echo '<br>';
//            echo '<br>';
//            var_dump($product->stocks_expected);
//            echo '<br>';
//            echo '<br>';
//            echo '<br>';
//            echo '<br>';
//            echo '<br>';
//        }
//        exit;
        return view('content.main.index');
    }
}
