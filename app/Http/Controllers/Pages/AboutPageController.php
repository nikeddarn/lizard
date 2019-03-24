<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;

class AboutPageController extends Controller
{
    /**
     * @var string
     */
    const CONTACT_PAGE_ROUTE_NAME = 'shop.about.index';
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
        $pageData = $this->staticPage->newQuery()->where('route', self::CONTACT_PAGE_ROUTE_NAME)->first();

        return view('content.pages.about.index')->with(compact('pageData'));
    }
}
