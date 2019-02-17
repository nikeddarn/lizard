<?php

namespace App\Http\Controllers\Settings;

use App\Contracts\Shop\LocalesInterface;
use App\Http\Requests\Admin\Settings\UpdateSeoSettingsRequest;
use App\Support\Settings\SettingsRepository;
use App\Http\Controllers\Controller;

class SeoSettingsController extends Controller
{
    /**
     * @var string
     */
    const CATEGORY_METADATA_KEY = 'seo.metadata.category';
    /**
     * @var string
     */
    const LEAF_CATEGORY_METADATA_KEY = 'seo.metadata.leaf_category';
    /**
     * @var string
     */
    const VIRTUAL_CATEGORY_METADATA_KEY = 'seo.metadata.virtual_category';
    /**
     * @var string
     */
    const FILTERED_CATEGORY_METADATA_KEY = 'seo.metadata.filtered_category';
    /**
     * @var string
     */
    const PRODUCT_METADATA_KEY = 'seo.metadata.product';
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * SeoSettingsController constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Edit SEO settings.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $categorySeoData = $this->settingsRepository->getProperty(self::CATEGORY_METADATA_KEY);

        $leafCategorySeoData = $this->settingsRepository->getProperty(self::LEAF_CATEGORY_METADATA_KEY);

        $virtualCategorySeoData = $this->settingsRepository->getProperty(self::VIRTUAL_CATEGORY_METADATA_KEY);

        $filteredCategorySeoData = $this->settingsRepository->getProperty(self::FILTERED_CATEGORY_METADATA_KEY);

        $productSeoData = $this->settingsRepository->getProperty(self::PRODUCT_METADATA_KEY);

        return view('content.admin.settings.seo.index')->with(compact('categorySeoData', 'leafCategorySeoData',  'virtualCategorySeoData', 'filteredCategorySeoData', 'productSeoData'));
    }

    /**
     * Update SEO settings.
     *
     * @param UpdateSeoSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateSeoSettingsRequest $request)
    {
        $categorySeoData = [
            LocalesInterface::RU => [
                'title' => $request->get('category_title_ru'),
                'description' => $request->get('category_description_ru'),
                'keywords' => $request->get('category_keywords_ru'),
            ],
            LocalesInterface::UK => [
                'title' => $request->get('category_title_uk'),
                'description' => $request->get('category_description_uk'),
                'keywords' => $request->get('category_keywords_uk'),
            ],
        ];

        $leafCategorySeoData = [
            LocalesInterface::RU => [
                'title' => $request->get('leaf_category_title_ru'),
                'description' => $request->get('leaf_category_description_ru'),
                'keywords' => $request->get('leaf_category_keywords_ru'),
            ],
            LocalesInterface::UK => [
                'title' => $request->get('leaf_category_title_uk'),
                'description' => $request->get('leaf_category_description_uk'),
                'keywords' => $request->get('leaf_category_keywords_uk'),
            ],
        ];

        $virtualCategorySeoData = [
            LocalesInterface::RU => [
                'name' => $request->get('virtual_category_name_ru'),
                'title' => $request->get('virtual_category_title_ru'),
                'description' => $request->get('virtual_category_description_ru'),
                'keywords' => $request->get('virtual_category_keywords_ru'),
            ],
            LocalesInterface::UK => [
                'name' => $request->get('virtual_category_name_uk'),
                'title' => $request->get('virtual_category_title_uk'),
                'description' => $request->get('virtual_category_description_uk'),
                'keywords' => $request->get('virtual_category_keywords_uk'),
            ],
        ];

        $filteredCategorySeoData = [
            LocalesInterface::RU => [
                'name' => $request->get('filtered_category_name_ru'),
                'title' => $request->get('filtered_category_title_ru'),
                'description' => $request->get('filtered_category_description_ru'),
                'keywords' => $request->get('filtered_category_keywords_ru'),
            ],
            LocalesInterface::UK => [
                'name' => $request->get('filtered_category_name_uk'),
                'title' => $request->get('filtered_category_title_uk'),
                'description' => $request->get('filtered_category_description_uk'),
                'keywords' => $request->get('filtered_category_keywords_uk'),
            ],
        ];

        $productSeoData = [
            LocalesInterface::RU => [
                'title' => $request->get('product_title_ru'),
                'description' => $request->get('product_description_ru'),
                'keywords' => $request->get('product_keywords_ru'),
            ],
            LocalesInterface::UK => [
                'title' => $request->get('product_title_uk'),
                'description' => $request->get('product_description_uk'),
                'keywords' => $request->get('product_keywords_uk'),
            ],
        ];

        $this->settingsRepository->setProperty(self::CATEGORY_METADATA_KEY, $categorySeoData);
        $this->settingsRepository->setProperty(self::LEAF_CATEGORY_METADATA_KEY, $leafCategorySeoData);
        $this->settingsRepository->setProperty(self::VIRTUAL_CATEGORY_METADATA_KEY, $virtualCategorySeoData);
        $this->settingsRepository->setProperty(self::FILTERED_CATEGORY_METADATA_KEY, $filteredCategorySeoData);
        $this->settingsRepository->setProperty(self::PRODUCT_METADATA_KEY, $productSeoData);

        return redirect(route('admin.settings.seo.edit'))->with([
            'successful' => true,
        ]);
    }
}
