<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Support\Shop\Products\RecentProducts;
use Illuminate\View\View;

class RecentProductController extends Controller
{
    /**
     * @var RecentProducts
     */
    private $recentProducts;

    /**
     * FavouriteProductController constructor.
     * @param RecentProducts $recentProducts
     */
    public function __construct(RecentProducts $recentProducts)
    {
        $this->recentProducts = $recentProducts;
    }

    /**
     * Show user'd recent products.
     *
     * @return View
     */
    public function index()
    {

        if (auth('web')->check()) {
            $view = view('content.user.recent.registered.index');
        } else {
            $view = view('content.user.recent.unregistered.index');
        }

        $recentProducts = $this->recentProducts->getProducts();

        return $view->with(compact('recentProducts'));
    }
}
