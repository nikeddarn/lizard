<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\Storage;

class ContactsPageController extends Controller
{
    /**
     * @var string
     */
    const CONTACT_PAGE_ROUTE_NAME = 'shop.contacts.index';
    /**
     * @var Storage
     */
    private $storage;
    /**
     * @var StaticPage
     */
    private $staticPage;

    /**
     * ContactsPageController constructor.
     * @param Storage $storage
     * @param StaticPage $staticPage
     */
    public function __construct(Storage $storage, StaticPage $staticPage)
    {
        $this->storage = $storage;
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

        $storages = $this->storage->newQuery()->with('storagePhones', 'workDays')->get();

        return view('content.pages.contacts.index')->with(compact('pageData', 'storages'));
    }
}
