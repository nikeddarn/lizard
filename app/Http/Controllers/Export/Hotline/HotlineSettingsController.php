<?php

namespace App\Http\Controllers\Export\Hotline;

use App\Http\Controllers\Controller;
use App\Http\Requests\Export\Hotline\HotlineSettingsRequest;
use App\Support\Settings\SettingsRepository;
use Illuminate\View\View;

class HotlineSettingsController extends Controller
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * HotlineExportController constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Edit shop settings.
     *
     * @return View
     */
    public function edit()
    {
        $settings = $this->settingsRepository->getProperty('export.hotline.common');

        return view('content.admin.export.hotline.settings.index')->with(compact('settings'));
    }

    public function update(HotlineSettingsRequest $request)
    {
        $this->settingsRepository->setProperty('export.hotline.common', [
            'firm_name' => $request->get('firm_name'),
            'firm_id' => $request->get('firm_id'),
        ]);

        return back()->with([
            'successful' => true,
        ]);
    }
}
