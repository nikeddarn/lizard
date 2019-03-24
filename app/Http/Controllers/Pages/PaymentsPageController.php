<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;

class PaymentsPageController extends Controller
{
    /**
     * @var string
     */
    const PAYMENTS_PAGE_ROUTE_NAME = 'shop.payments.index';
    /**
     * @var StaticPage
     */
    private $staticPage;

    /**
     * ContactsPageController constructor.
     * @param StaticPage $staticPage
     */
    public function __construct(StaticPage $staticPage)
    {
        $this->staticPage = $staticPage;
    }


    /**
     * Show main page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pageData = $this->staticPage->newQuery()->where('route', self::PAYMENTS_PAGE_ROUTE_NAME)->first();

        return view('content.pages.payments.index')->with(compact('pageData'));
    }
}
