<?php

namespace App\Http\Controllers\Settings;

use App\Contracts\Shop\ProductBadgesInterface;
use App\Http\Requests\Admin\Settings\UpdateShopSettingsRequest;
use App\Support\Settings\SettingsRepository;
use App\Http\Controllers\Controller;

class ShopSettingsController extends Controller
{
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * ShopSettingsController constructor.
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
        $currenciesData = [
            'exchange_rate' => $this->settingsRepository->getProperty('currencies.exchange_rate'),
            'show_usd_price' => $this->settingsRepository->getProperty('currencies.show_usd_price'),
        ];

        $productData = [
            'show_products_per_page' => $this->settingsRepository->getProperty('shop.show_products_per_page'),
            'show_product_comments_per_page' => $this->settingsRepository->getProperty('shop.show_product_comments_per_page'),
            'recent_product_ttl' => $this->settingsRepository->getProperty('shop.recent_product_ttl'),
            'delete_product' => $this->settingsRepository->getProperty('shop.delete_product'),
            'show_rate' => $this->settingsRepository->getProperty('shop.show_rate'),
            'show_defect_rate' => $this->settingsRepository->getProperty('shop.show_defect_rate'),
        ];

        $badgesData = $this->settingsRepository->getProperty('badges');

        $filtersData = $this->settingsRepository->getProperty('shop.products_filters_show');

        return view('content.admin.settings.shop.index')->with(compact('currenciesData', 'badgesData', 'productData', 'filtersData'));
    }

    /**
     * Update SEO settings.
     *
     * @param UpdateShopSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateShopSettingsRequest $request)
    {
        // currencies
        $this->settingsRepository->setProperty('currencies.exchange_rate', [
            'get_exchange_rate' => $request->get('get_exchange_rate'),
            'ttl' => (int)$request->get('update_rate_time'),
            'usd_rate' => (float)$request->get('usd_rate'),
        ]);
        $this->settingsRepository->setProperty('currencies.show_usd_price', [
            'allowed' => $request->has('allow_usd_price'),
            'min_user_price_group' => (int)$request->get('min_user_price_group'),
        ]);

        // shop
        $this->settingsRepository->setProperty('shop.show_products_per_page', $request->get('show_products_per_page'));
        $this->settingsRepository->setProperty('shop.show_product_comments_per_page', $request->get('show_product_comments_per_page'));
        $this->settingsRepository->setProperty('shop.recent_product_ttl', $request->get('recent_product_ttl'));

        $this->settingsRepository->setProperty('shop.delete_product', [
            'delete_product_on_delete_category' => $request->has('delete_product_on_delete_category'),
            'archive_product_on_delete' => $request->has('archive_product_on_delete'),
        ]);

        $this->settingsRepository->setProperty('shop.show_rate', [
            'allowed' => $request->has('allow_show_product_rate'),
            'count' => $request->get('show_product_rate_from_review_counts'),
        ]);
        $this->settingsRepository->setProperty('shop.show_defect_rate', [
            'allowed' => $request->has('allow_show_product_defect_rate'),
            'count' => $request->get('show_product_defect_rate_from_sold_counts'),
        ]);

        // badges
        $this->settingsRepository->setProperty('badges', [
            'ttl' => [
                ProductBadgesInterface::NEW => $request->get('new_product_badge_ttl'),
                ProductBadgesInterface::PRICE_DOWN => $request->get('price_down_badge_ttl'),
            ],
            ProductBadgesInterface::ENDING => $request->get('ending_badge_products_count'),
        ]);

        // filters
        $this->settingsRepository->setProperty('shop.products_filters_show', [
            'min' => $request->get('min_show_filters_per_page'),
            'max' => $request->get('max_show_filters_per_page'),
            'max_values_count' => $request->get('max_items_to_open_filter'),
        ]);

        return redirect(route('admin.settings.shop.edit'))->with([
            'successful' => true,
        ]);
    }
}
