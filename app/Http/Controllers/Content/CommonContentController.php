<?php

namespace App\Http\Controllers\Content;

use App\Http\Requests\Content\UpdateCommonContentRequest;
use App\Support\Settings\SettingsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class CommonContentController extends Controller
{
    /**
     * @var string
     */
    const HEADER_PHONES_DATA_KEY = 'content.common.header.phones';

    /**
     * SeoSettingsController constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
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

        $headerPhones = $this->settingsRepository->getProperty(self::HEADER_PHONES_DATA_KEY);

        return view('content.admin.page_content.common.index')->with(compact('headerPhones'));
    }

    /**
     * Update common settings.
     *
     * @param UpdateCommonContentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCommonContentRequest $request)
    {
        if (Gate::denies('local-catalog-edit', auth('web')->user())) {
            abort(401);
        }

        $headerPhones = $request->get('header_phone');

        $this->settingsRepository->setProperty(self::HEADER_PHONES_DATA_KEY, $headerPhones);

        return back()->with([
            'successful' => true,
        ]);
    }
}
