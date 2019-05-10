<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Support\Shop\Products\RecentProducts;
use Illuminate\View\View;

class RecentProductController extends Controller
{
    /**
     * @var RecentProducts
     */
    private $recentProducts;
    /**
     * @var StaticPage
     */
    private $staticPage;

    /**
     * FavouriteProductController constructor.
     * @param RecentProducts $recentProducts
     * @param StaticPage $staticPage
     */
    public function __construct(RecentProducts $recentProducts, StaticPage $staticPage)
    {
        $this->recentProducts = $recentProducts;
        $this->staticPage = $staticPage;
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

        $pageData = $this->staticPage->newQuery()->where('route', 'user.recent.index')->first();

        $locale = app()->getLocale();

        $pageTitle = $pageData->{'title_' . $locale};
        $noindexPage = true;

        return $view->with(compact('recentProducts', 'pageTitle', 'noindexPage'));
    }
}
