<?php

namespace App\Http\Controllers\Content;

use App\Http\Requests\Content\ProductGroup\SortGroupsRequest;
use App\Http\Requests\Content\Slide\SortSlidesRequest;
use App\Http\Requests\Content\UpdateMainPageSeoDataRequest;
use App\Models\ProductGroup;
use App\Models\Slide;
use App\Models\Slider;
use App\Models\StaticPage;
use App\Http\Controllers\Controller;

class MainContentController extends Controller
{
    /**
     * @var string
     */
    const MAIN_PAGE_ROUTE_NAME = 'main';
    /**
     * @var string
     */
    const MAIN_PAGE_SLIDER_KEY = 'main_page_top_slider';
    /**
     * @var StaticPage
     */
    private $staticPage;
    /**
     * @var Slider
     */
    private $slider;
    /**
     * @var Slide
     */
    private $slide;
    /**
     * @var ProductGroup
     */
    private $productGroup;

    /**
     * SeoSettingsController constructor.
     * @param StaticPage $staticPage
     * @param Slider $slider
     * @param Slide $slide
     * @param ProductGroup $productGroup
     */
    public function __construct(StaticPage $staticPage, Slider $slider, Slide $slide, ProductGroup $productGroup)
    {
        $this->staticPage = $staticPage;
        $this->slider = $slider;
        $this->slide = $slide;
        $this->productGroup = $productGroup;
    }

    /**
     * Edit common settings.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $pageData = $this->staticPage->newQuery()->where('route', self::MAIN_PAGE_ROUTE_NAME)
            ->first();

        $mainSlider = $this->slider->newQuery()
            ->where('key', self::MAIN_PAGE_SLIDER_KEY)
            ->with(['slides' => function ($query) {
                $query->orderBy('position');
            }])
            ->first();

        $productGroups = $this->productGroup->newQuery()->orderBy('position')->with('castProductMethod', 'category')->get();

        return view('content.admin.page_content.main.index')->with(compact('pageData', 'mainSlider', 'productGroups'));
    }

    /**
     * Update common settings.
     *
     * @param UpdateMainPageSeoDataRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSeo(UpdateMainPageSeoDataRequest $request)
    {
        $pageData = [
            'title_ru' => $request->get('title_ru'),
            'title_uk' => $request->get('title_uk'),
            'description_ru' => $request->get('description_ru'),
            'description_uk' => $request->get('description_uk'),
            'keywords_ru' => $request->get('keywords_ru'),
            'keywords_uk' => $request->get('keywords_uk'),
        ];

        $this->staticPage->newQuery()
            ->where('route', self::MAIN_PAGE_ROUTE_NAME)
            ->update($pageData);

        return back()->with([
            'successful' => true,
        ]);
    }

    /**
     * Sort slider images.
     *
     * @param SortSlidesRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sortSlides(SortSlidesRequest $request)
    {
        $slidesIds = $request->get('slide_id');

        foreach ($slidesIds as $index => $slideId) {
            $this->slide->newQuery()->where('id', $slideId)->update([
                'position' => $index + 1,
            ]);
        }

        return back()->with([
            'successful' => true,
        ]);
    }

    /**
     * Sort slider images.
     *
     * @param SortGroupsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sortGroups(SortGroupsRequest $request)
    {
        $groupsIds = $request->get('group_id');

        foreach ($groupsIds as $index => $groupId) {
            $this->productGroup->newQuery()->where('id', $groupId)->update([
                'position' => $index + 1,
            ]);
        }

        return back()->with([
            'successful' => true,
        ]);
    }
}
