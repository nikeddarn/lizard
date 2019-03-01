<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorProduct;
use App\Support\Vendors\Providers\BrainInsertProductProvider;
use Carbon\Carbon;
use DateTime;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use stdClass;

class MainPageController extends Controller
{
    public function show()
    {
//        $uuid = Str::uuid();
//
//        $user = new User();
//        $user->setRememberToken($uuid);
//        $user->save();
////
//        $cookieName = $user->getRememberTokenName();
//
//        auth('web')->login($user, true);
//        auth('web')->logout();

//        $user = User::query()->where('remember_token', request()->cookie('remember_token'))->first();

//var_dump($user);
//var_dump(auth('web')->user());
//        echo(auth()->guard('web')->getProvider());exit;
//        $uuid = Str::uuid();
//        return response(view('content.main.index'))->withCookie(cookie()->forever($cookieName, $uuid));
//        return response(view('content.main.index'));
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
