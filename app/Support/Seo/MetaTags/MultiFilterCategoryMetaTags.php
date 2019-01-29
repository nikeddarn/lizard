<?php
/**
 * Create multi filtered category's meta tags values.
 */

namespace App\Support\Seo\MetaTags;


use App\Models\Category;
use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MultiFilterCategoryMetaTags
{
    /**
     * @var string
     */
    const METADATA_STORAGE_KEY = 'seo.metadata.filtered_category';

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
    private $namePattern;
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
     * CategoryMetaTags constructor.
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Get category's title.
     *
     * @param Category|Model $category
     * @param Collection $attributeValues
     * @return array
     */
    public function getCategoryName(Category $category, Collection $attributeValues)
    {
        if (empty($this->namePattern)) {
            $this->createCategoryPatterns($category, $attributeValues);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->namePattern);
    }

    /**
     * Get category's title.
     *
     * @param Category|Model $category
     * @param Collection $attributeValues
     * @return array
     */
    public function getCategoryTitle(Category $category, Collection $attributeValues)
    {
        if (empty($this->titlePattern)) {
            $this->createCategoryPatterns($category, $attributeValues);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->titlePattern);
    }

    /**
     * Get category's description.
     *
     * @param Category|Model $category
     * @param Collection $attributeValues
     * @return array
     */
    public function getCategoryDescription(Category $category, Collection $attributeValues)
    {
        if (empty($this->descriptionPattern)) {
            $this->createCategoryPatterns($category, $attributeValues);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->descriptionPattern);
    }

    /**
     * Get category's keywords.
     *
     * @param Category|Model $category
     * @param Collection $attributeValues
     * @return string
     */
    public function getCategoryKeywords(Category $category, Collection $attributeValues):string
    {
        if ($category->keywords){
            return $category->keywords;
        }

        if (empty($this->keywordsPattern)) {
            $this->createCategoryPatterns($category, $attributeValues);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->keywordsPattern);
    }

    /**
     * @param Category $category
     * @param Collection $attributeValues
     */
    protected function createCategoryPatterns(Category $category, Collection $attributeValues)
    {
        // retrieve meta data patterns
        $metaDataPatterns = $this->settingsRepository->getProperty(self::METADATA_STORAGE_KEY);

        // get locale
        $locale = app()->getLocale();

        $this->namePattern = $metaDataPatterns[$locale]['name'];
        $this->titlePattern = $metaDataPatterns[$locale]['title'];
        $this->descriptionPattern = $metaDataPatterns[$locale]['description'];
        $this->keywordsPattern = $metaDataPatterns[$locale]['keywords'];

        $this->replacementPatterns = [
            '/CATEGORY_NAME/',
            '/FILTERS_VALUES/',
            '/FILTERS_WITH_VALUES/'
        ];

        $this->replacementValues = [
            $category->name,
            $this->implodeFilterValues($attributeValues),
            $this->implodeFiltersNamesWithValues($attributeValues),
        ];
    }

    /**
     * Implode filters names to string.
     *
     * @param Collection $attributeValues
     * @return string
     */
    private function implodeFilterValues(Collection $attributeValues):string
    {
        return implode(',', $attributeValues->pluck('value')->toArray());
    }

    /**
     * Implode filters names with its selected values to string.
     *
     * @param Collection $attributeValues
     * @return string
     */
    private function implodeFiltersNamesWithValues(Collection $attributeValues):string
    {
        $filtersWithValues = [];

        $filters = [];

        foreach ($attributeValues as $attributeValue){
            $filters[$attributeValue->attribute->name][] = $attributeValue->value;
        }

        foreach ($filters as $filterName => $filterValues){
            $filtersWithValues[] = $filterName . ':' . implode(', ', $filterValues);
        }

        return implode('; ', $filtersWithValues);
    }
}
