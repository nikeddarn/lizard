<?php

namespace App\Http\Controllers;

use App\Models\VendorProduct;
use App\Support\Vendors\Providers\BrainInsertProductProvider;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use stdClass;

class MainPageController extends Controller
{
    public function show()
    {
//var_dump(explode(',', ''));exit;
//        var_dump(http_build_query([]));exit;
//
//        $provider = app()->make(BrainInsertProductProvider::class);
//        foreach (VendorProduct::query()
//                     ->orderByDesc('id')
//                     ->limit(1)
////                     ->offset(100)
//                     ->get()->pluck('vendor_product_id')->toArray() as $prid) {
//            $product = $provider->getProductData($prid)['product_data_ru'];
//            var_dump($product);
//            echo '<br>';
//            echo '<br>';
////            var_dump($product->stocks);
//            echo '<br>';
//            echo '<br>';
////            var_dump($product->available);
//            echo '<br>';
//            echo '<br>';
////            var_dump($product->stocks_expected);
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
