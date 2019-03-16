<?php

namespace App\Http\Controllers\Content;

use App\Http\Requests\Content\Slide\DeleteSlideRequest;
use App\Http\Requests\Content\Slide\InsertSlideRequest;
use App\Http\Requests\Content\Slide\UpdateSlideRequest;
use App\Models\Slide;
use App\Models\Slider;
use App\Support\ImageHandlers\SliderImageHandler;
use App\Http\Controllers\Controller;

class SlideController extends Controller
{

    /**
     * @var Slide
     */
    private $slide;
    /**
     * @var Slider
     */
    private $slider;
    /**
     * @var SliderImageHandler
     */
    private $imageHandler;

    /**
     * SeoSettingsController constructor.
     * @param Slider $slider
     * @param Slide $slide
     * @param SliderImageHandler $imageHandler
     */
    public function __construct(Slider $slider, Slide $slide, SliderImageHandler $imageHandler)
    {
        $this->slide = $slide;
        $this->slider = $slider;
        $this->imageHandler = $imageHandler;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $sliderId
     * @return \Illuminate\Http\Response
     */
    public function create(string $sliderId)
    {
        $slider = $this->slider->newQuery()->findOrFail($sliderId);

        return view('content.admin.page_content.slide.create.index')->with(compact('slider'));
    }

    /**
     * Store slide.
     *
     * @param InsertSlideRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsertSlideRequest $request)
    {
        $sliderId = $request->get('slider_id');

        $slider = $this->slider->newQuery()->with('slides')->findOrFail($sliderId);

        $slidePosition = (int)$slider->slides->max('position') + 1;

        $slideData = [
            'sliders_id' => $sliderId,
            'image_ru' => $this->imageHandler->createSliderImage($sliderId, $request->image_ru),
            'image_uk' => $this->imageHandler->createSliderImage($sliderId, $request->image_uk),
            'name_ru' => $request->get('name_ru'),
            'name_uk' => $request->get('name_uk'),
            'text_ru' => $request->get('text_ru'),
            'text_uk' => $request->get('text_uk'),
            'url_ru' => $request->get('url_ru'),
            'url_uk' => $request->get('url_uk'),
            'position' => $slidePosition,
        ];

        $this->slide->newQuery()->create($slideData);

        return redirect(route('admin.content.main.edit'));
    }

    /**
     * Edit common settings.
     *
     * @param string $slideId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $slideId)
    {
        $slide = $this->slide->newQuery()->with('slider')->findOrFail($slideId);

        $slider = $slide->slider;

        return view('content.admin.page_content.slide.edit.index')->with(compact('slider', 'slide'));
    }

    /**
     * Update common settings.
     *
     * @param UpdateSlideRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSlideRequest $request)
    {
        $slideId = $request->get('slide_id');

        $slide = $this->slide->newQuery()->findOrFail($slideId);

        $slideData = [
            'name_ru' => $request->get('name_ru'),
            'name_uk' => $request->get('name_uk'),
            'text_ru' => $request->get('text_ru'),
            'text_uk' => $request->get('text_uk'),
            'url_ru' => $request->get('url_ru'),
            'url_uk' => $request->get('url_uk'),
        ];

        $slide->update($slideData);

        return redirect(route('admin.content.main.edit'));
    }

    /**
     * Delete slider image.
     *
     * @param DeleteSlideRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(DeleteSlideRequest $request)
    {
        $slideId = $request->get('slide_id');

        $slide = $this->slide->newQuery()->findOrFail($slideId);

        $this->imageHandler->deleteSliderImage($slide->image_ru);
        $this->imageHandler->deleteSliderImage($slide->image_uk);

        $slide->delete();

        return back();
    }
}
