<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Admin\Settings\UpdateVendorSettingsRequest;
use App\Models\Vendor;
use App\Support\Settings\SettingsRepository;
use App\Http\Controllers\Controller;

class VendorSettingsController extends Controller
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * SeoSettingsController constructor.
     * @param SettingsRepository $settingsRepository
     * @param Vendor $vendor
     */
    public function __construct(SettingsRepository $settingsRepository, Vendor $vendor)
    {
        $this->settingsRepository = $settingsRepository;
        $this->vendor = $vendor;
    }

    /**
     * Edit shop settings.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $vendorsData = [
            'min_profit_sum_to_offer_product' => $this->settingsRepository->getProperty('vendor.min_profit_sum_to_offer_product'),
            'update_own_product_price_on_vendor_sync' => $this->settingsRepository->getProperty('vendor.update_own_product_price_on_vendor_sync'),
            'use_vendor_available_product_to_calculate_price' => $this->settingsRepository->getProperty('vendor.use_vendor_available_product_to_calculate_price'),
            'min_profit_sum_to_price_discount' => $this->settingsRepository->getProperty('vendor.min_profit_sum_to_price_discount'),
            'min_profit_percents_to_price_discount' => $this->settingsRepository->getProperty('vendor.min_profit_percents_to_price_discount'),
            'column_discounts' => $this->settingsRepository->getProperty('vendor.column_discounts'),
        ];

        return view('content.admin.settings.vendors.index')->with(compact('vendors', 'vendorsData'));
    }

    /**
     * Update SEO settings.
     *
     * @param UpdateVendorSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateVendorSettingsRequest $request)
    {
        $this->settingsRepository->setProperty('vendor.min_profit_sum_to_offer_product', $request->get('min_profit_sum_to_offer_product'));
        $this->settingsRepository->setProperty('vendor.update_own_product_price_on_vendor_sync', $request->has('update_own_product_price_on_vendor_sync'));
        $this->settingsRepository->setProperty('vendor.use_vendor_available_product_to_calculate_price', $request->has('use_vendor_available_product_to_calculate_price'));
        $this->settingsRepository->setProperty('vendor.min_profit_sum_to_price_discount', $request->get('min_profit_sum_to_price_discount'));
        $this->settingsRepository->setProperty('vendor.min_profit_percents_to_price_discount', $request->get('min_profit_percents_to_price_discount'));
        $this->settingsRepository->setProperty('vendor.column_discounts', [
            'price1' => $request->get('discount_price1'),
            'price2' => $request->get('discount_price2'),
            'price3' => $request->get('discount_price3'),
        ]);

        return redirect(route('admin.settings.vendor.edit'))->with([
            'successful' => true,
        ]);
    }
}
