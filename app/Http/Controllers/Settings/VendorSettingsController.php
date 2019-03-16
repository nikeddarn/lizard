<?php

namespace App\Http\Controllers\Settings;

use App\Http\Requests\Admin\Settings\UpdateVendorSettingsRequest;
use App\Models\Vendor;
use App\Support\Settings\SettingsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

        $oldPriceDiscount = $this->settingsRepository->getProperty('vendor.price_discount');

        $this->settingsRepository->setProperty('vendor.insert_product', $insertProductSettings);

        $this->settingsRepository->setProperty('vendor.delete_product', $deleteProductSettings);

        $this->settingsRepository->setProperty('vendor.product_price_conditions', $productPriceConditions);

        $this->settingsRepository->setProperty('vendor.price_discount', $priceDiscount);

        // update products' prices
        if ($priceDiscount !== $oldPriceDiscount){
            $this->updateProductPrices($priceDiscount);
        }

        return redirect(route('admin.settings.vendor.edit'))->with([
            'successful' => true,
        ]);
    }

    /**
     * Update products' prices.
     *
     * @param array $priceDiscount
     */
    private function updateProductPrices(array $priceDiscount)
    {
        // update prices if profit sum or profit percents more than given
        DB::statement('UPDATE products p, (SELECT vp.id AS vendor_product_id, vp.products_id AS product_id, AVG(vp.price) AS avg_incoming_price, AVG(IFNULL(vp.recommendable_price, vp.retail_price)) AS avg_retail_price FROM vendor_products vp GROUP BY vp.products_id, vp.id) vpdata SET p.price1 = avg_retail_price - (avg_retail_price - avg_incoming_price) * LEAST(:price1_discount / 100, 1), p.price2 = avg_retail_price - (avg_retail_price - avg_incoming_price) * LEAST(:price2_discount / 100, 1), p.price3 = avg_retail_price - (avg_retail_price - avg_incoming_price) * LEAST(:price3_discount / 100, 1) WHERE p.id = vpdata.product_id AND avg_incoming_price IS NOT NULL AND avg_retail_price IS NOT NULL AND ((avg_retail_price - avg_incoming_price) >= :min_profit_sum OR ((avg_retail_price - avg_incoming_price) / avg_incoming_price * 100) >= :min_profit_percents)', [
            'price1_discount' => (float)$priceDiscount['column_discounts']['price1'],
            'price2_discount' => (float)$priceDiscount['column_discounts']['price2'],
            'price3_discount' => (float)$priceDiscount['column_discounts']['price3'],
            'min_profit_sum' => (float)$priceDiscount['min_profit_sum_to_price_discount'],
            'min_profit_percents' => (float)$priceDiscount['min_profit_percents_to_price_discount'],
        ]);

        // set retail price to each price columns if profit sum or profit percents less than given
        DB::statement('UPDATE products p, (SELECT vp.id AS vendor_product_id, vp.products_id AS product_id, AVG(vp.price) AS avg_incoming_price, AVG(IFNULL(vp.recommendable_price, vp.retail_price)) AS avg_retail_price FROM vendor_products vp GROUP BY vp.products_id, vp.id) vpdata SET p.price1 = avg_retail_price, p.price2 = avg_retail_price, p.price3 = avg_retail_price WHERE p.id = vpdata.product_id AND avg_incoming_price IS NOT NULL AND avg_retail_price IS NOT NULL AND ((avg_retail_price - avg_incoming_price) < :min_profit_sum AND ((avg_retail_price - avg_incoming_price) / avg_incoming_price * 100) < :min_profit_percents)', [
            'min_profit_sum' => (float)$priceDiscount['min_profit_sum_to_price_discount'],
            'min_profit_percents' => (float)$priceDiscount['min_profit_percents_to_price_discount'],
        ]);
    }
}
