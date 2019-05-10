<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\Storage;
use App\Support\Headers\CacheControlHeaders;
use App\Support\User\RetrieveUser;
use Illuminate\Http\Response;

class ContactsPageController extends Controller
{
    use RetrieveUser;
    use CacheControlHeaders;

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
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();

        $response = response()->make();

        $pageData = $this->staticPage->newQuery()->where('route', self::CONTACT_PAGE_ROUTE_NAME)->first();

        $pageLastModified = $pageData->updated_at;

        $this->checkAndSetLastModifiedHeader($user, $response, $pageLastModified);

        $storages = $this->storage->newQuery()->with('storagePhones', 'workDays')->get();

        $response->setContent(view('content.pages.contacts.index')->with(compact('pageData', 'storages')));

        $this->checkAndSetEtagHeader($user, $response);

        return $response;
    }
}
