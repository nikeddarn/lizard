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
            'insert_product' => $this->settingsRepository->getProperty('vendor.insert_product'),

            'delete_product' => $this->settingsRepository->getProperty('vendor.delete_product'),

            'product_price_conditions' => $this->settingsRepository->getProperty('vendor.product_price_conditions'),

            'price_discount' => $this->settingsRepository->getProperty('vendor.price_discount'),
        ];

        return view('content.admin.settings.vendors.index')->with(compact('vendorsData'));
    }

    /**
     * Update SEO settings.
     *
     * @param UpdateVendorSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateVendorSettingsRequest $request)
    {
        $insertProductSettings = [
            'download_archive_product' => $request->has('download_archive_product'),
        ];

        $deleteProductSettings = [
            'delete_product_on_delete_vendor_category' => $request->has('delete_product_on_delete_vendor_category'),
            'keep_link_in_stock_present_product_on_delete' => $request->has('keep_link_in_stock_present_product_on_delete'),
            'delete_empty_local_category_on_delete_vendor_category' => $request->has('delete_empty_local_category_on_delete_vendor_category'),
            'delete_product_on_archive_vendor_product' => $request->has('delete_product_on_archive_vendor_product'),
        ];

        $productPriceConditions = [
            'update_own_product_price_on_vendor_sync' => $request->has('update_own_product_price_on_vendor_sync'),
            'use_vendor_available_product_to_calculate_price' => $request->has('use_vendor_available_product_to_calculate_price'),
        ];

        $priceDiscount = [
            'min_profit_sum_to_price_discount' => $request->get('min_profit_sum_to_price_discount'),
            'min_profit_percents_to_price_discount' => $request->get('min_profit_percents_to_price_discount'),
            'column_discounts' => [
                'price1' => $request->get('discount_price1'),
                'price2' => $request->get('discount_price2'),
                'price3' => $request->get('discount_price3'),
            ]
        ];

        $this->settingsRepository->setProperty('vendor.insert_product', $insertProductSettings);

        $this->settingsRepository->setProperty('vendor.delete_product', $deleteProductSettings);

        $this->settingsRepository->setProperty('vendor.product_price_conditions', $productPriceConditions);

        $this->settingsRepository->setProperty('vendor.price_discount', $priceDiscount);

        return redirect(route('admin.settings.vendor.edit'))->with([
            'successful' => true,
        ]);
    }
}
