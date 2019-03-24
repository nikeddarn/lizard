<?php

namespace App\Http\Controllers\Content;

use App\Http\Requests\Content\UpdateStaticPageDataRequest;
use App\Models\StaticPage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContactsContentController extends Controller
{
    /**
     * @var string
     */
    const CONTACT_PAGE_ROUTE_NAME = 'shop.contacts.index';
    /**
     * @var StaticPage
     */
    private $staticPage;

    /**
     * SeoSettingsController constructor.
     * @param StaticPage $staticPage
     */
    public function __construct(StaticPage $staticPage)
    {
        $this->staticPage = $staticPage;
    }

    /**
     * Edit common settings.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $pageData = $this->staticPage->newQuery()->where('route', self::CONTACT_PAGE_ROUTE_NAME)
            ->first();

        return view('content.admin.page_content.contacts.index')->with(compact('pageData'));
    }

    /**
     * Update common settings.
     *
     * @param UpdateStaticPageDataRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStaticPageDataRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $pageData = [
            'title_ru' => $request->get('title_ru'),
            'title_uk' => $request->get('title_uk'),
            'description_ru' => $request->get('description_ru'),
            'description_uk' => $request->get('description_uk'),
            'keywords_ru' => $request->get('keywords_ru'),
            'keywords_uk' => $request->get('keywords_uk'),
            'content_ru' => $request->get('content_ru'),
            'content_uk' => $request->get('content_uk'),
        ];

        $this->staticPage->newQuery()
            ->where('route', self::CONTACT_PAGE_ROUTE_NAME)
            ->update($pageData);

        return back()->with([
            'successful' => true,
        ]);
    }

    /**
     * Store image on public disk. Return image url.
     *
     * @param Request $request
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadImage(Request $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        if (!($request->ajax() && $request->hasFile('image'))) {
            return abort(405);
        }

        $this->validate($request, ['image' => 'image']);

        return '/storage/' . $request->image->store('images/content/contacts', 'public');
    }
}
