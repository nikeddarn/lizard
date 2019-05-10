<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Support\Headers\CacheControlHeaders;
use App\Support\User\RetrieveUser;
use Illuminate\Http\Response;

class AboutPageController extends Controller
{
    use RetrieveUser;
    use CacheControlHeaders;

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
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();

        $response = response()->make();

        $pageData = $this->staticPage->newQuery()->where('route', self::CONTACT_PAGE_ROUTE_NAME)->first();

        $pageLastModified = $pageData->updated_at;

        $this->checkAndSetLastModifiedHeader($user, $response, $pageLastModified);

        $response->setContent(view('content.pages.about.index')->with(compact('pageData')));

        $this->checkAndSetEtagHeader($user, $response);

        return $response;
    }
}
