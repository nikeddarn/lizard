<?php
/**
 * Create product's meta tags values.
 */

namespace App\Support\Seo\MetaTags;


use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\Settings\SettingsRepository;
use Illuminate\Support\Collection;

class ProductMetaTags
{
    /**
     * @var string
     */
    const METADATA_STORAGE_KEY = 'seo.metadata.product';

    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * @var string
     */
    private $replacementPatterns;
    /**
     * @var string
     */
    private $replacementValues;
    /**
     * @var string
     */
    private $titlePattern;
    /**
     * @var string
     */
    private $descriptionPattern;
    /**
     * @var string
     */
    private $keywordsPattern;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;

    /**
     * CategoryMetaTags constructor.
     * @param SettingsRepository $settingsRepository
     * @param ExchangeRates $exchangeRates
     */
    public function __construct(SettingsRepository $settingsRepository, ExchangeRates $exchangeRates)
    {
        $this->settingsRepository = $settingsRepository;
        $this->exchangeRates = $exchangeRates;
    }

    /**
     * Get category's title.
     *
     * @param Product $product
     * @return array
     */
    public function getCategoryTitle(Product $product)
    {
        if ($product->title) {
            return $product->title;
        }

        if (empty($this->titlePattern)) {
            $this->createCategoryPatterns($product);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->titlePattern);
    }

    /**
     * Get category's description.
     *
     * @param Product $product
     * @return array
     */
    public function getCategoryDescription(Product $product)
    {
        if ($product->description) {
            return $product->description;
        }

        if (empty($this->descriptionPattern)) {
            $this->createCategoryPatterns($product);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->descriptionPattern);
    }

    /**
     * Get category's keywords.
     *
     * @param Product $product
     * @return string
     */
    public function getCategoryKeywords(Product $product): string
    {
        if ($product->keywords) {
            return $product->keywords;
        }

        if (empty($this->keywordsPattern)) {
            $this->createCategoryPatterns($product);
        }

        // unique not null keywords
        $keywords = array_unique(array_filter(explode(',', preg_replace($this->replacementPatterns, $this->replacementValues, $this->keywordsPattern))));

        return implode(',', $keywords);
    }

    /**
     * Create patterns.
     *
     * @param Product $product
     */
    private function createCategoryPatterns(Product $product)
    {
        // calculate product price
        $productPrice = number_format($product->price1 * $this->exchangeRates->getRate(), 0, '', '&thinsp;');

        // retrieve meta data patterns
        $metaDataPatterns = $this->settingsRepository->getProperty(self::METADATA_STORAGE_KEY);

        // get locale
        $locale = app()->getLocale();

        $this->titlePattern = $metaDataPatterns[$locale]['title'];
        $this->descriptionPattern = $metaDataPatterns[$locale]['description'];
        $this->keywordsPattern = $metaDataPatterns[$locale]['keywords'];

        $this->replacementPatterns = [
            '/PRODUCT_NAME/',
            '/ATTRIBUTES_WITH_VALUES/',
            '/\[([^\[]*)PRODUCT_MODEL([^\]]*)\]/',
            '/\[([^\[]*)PRODUCT_ARTICUL([^\]]*)\]/',
            '/\[([^\[]*)PRODUCT_CODE([^\]]*)\]/',
            '/\[([^\[]*)PRODUCT_MANUFACTURER([^\]]*)\]/',
            '/\[([^\[]*)PRODUCT_PRICE([^\]]*)\]/',
            '/\[([^\[]*)PRODUCT_WARRANTY([^\]]*)\]/',
        ];

        $this->replacementValues = [
            $product->name,
            $this->implodeFiltersNamesWithValues($product->attributeValues),
            $product->model ? "$1 $product->model $2" : '',
            $product->articul ? "$1 $product->articul $2" : '',
            $product->code ? "$1 $product->code $2" : '',
            $product->manufacturer ? "$1 $product->manufacturer $2" : '',
            $product->price1 ? "$1 $productPrice $2" : '',
            $product->warranty ? "$1 $product->warranty $2" : '',
        ];
    }

    /**
     * Implode filters names with its selected values to string.
     *
     * @param Collection $attributeValues
     * @return string
     */
    private function implodeFiltersNamesWithValues(Collection $attributeValues): string
    {
        $filtersWithValues = [];

        $filters = [];

        foreach ($attributeValues as $attributeValue) {
            $filters[$attributeValue->attribute->name][] = $attributeValue->value;
        }

        foreach ($filters as $filterName => $filterValues) {
            $filtersWithValues[] = $filterName . ' - ' . implode(', ', $filterValues);
        }

        return implode('; ', $filtersWithValues);
    }
}
