<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Admin\Settings\UpdateVendorSettingsRequest;
use App\Support\Settings\SettingsRepository;
use App\Http\Controllers\Controller;

class OrderSettingsController extends Controller
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * OrderSettingsController constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Edit shop settings.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $ordersData = [
            'delivery' => $this->settingsRepository->getProperty('shop.order.delivery'),
        ];

        return view('content.admin.settings.orders.index')->with(compact('ordersData'));
    }

    /**
     * Update SEO settings.
     *
     * @param UpdateVendorSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateVendorSettingsRequest $request)
    {
        $deliverySettings = [
            'delivery_uah_price' => $request->get('delivery_uah_price'),
            'free_delivery_from_uah_sum' => $request->get('free_delivery_from_uah_sum'),
            'free_delivery_from_column' => $request->get('free_delivery_from_column'),
        ];

        $this->settingsRepository->setProperty('shop.order.delivery', $deliverySettings);

        return redirect(route('admin.settings.order.edit'))->with([
            'successful' => true,
        ]);
    }
}
