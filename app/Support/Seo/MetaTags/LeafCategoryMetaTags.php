<?php
/**
 * Create leaf category's meta tags values.
 */

namespace App\Support\Seo\MetaTags;


use App\Models\Category;
use App\Support\Settings\SettingsRepository;
use Illuminate\Database\Eloquent\Model;

class LeafCategoryMetaTags
{
    /**
     * @var string
     */
    const METADATA_STORAGE_KEY = 'seo.metadata.leaf_category';

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
     * @return array
     */
    public function getCategoryTitle(Category $category)
    {
        if ($category->title){
            return $category->title;
        }

        if (empty($this->titlePattern)) {
            $this->createCategoryPatterns($category);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->titlePattern);
    }

    /**
     * Get category's description.
     *
     * @param Category|Model $category
     * @return array
     */
    public function getCategoryDescription(Category $category)
    {
        if ($category->description){
            return $category->description;
        }

        if (empty($this->descriptionPattern)) {
            $this->createCategoryPatterns($category);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->descriptionPattern);
    }

    /**
     * Get category's keywords.
     *
     * @param Category $category
     * @return string
     */
    public function getCategoryKeywords(Category $category):string
    {
        if ($category->keywords){
            return $category->keywords;
        }

        if (empty($this->keywordsPattern)) {
            $this->createCategoryPatterns($category);
        }

        return preg_replace($this->replacementPatterns, $this->replacementValues, $this->keywordsPattern);
    }

    /**
     * @param Category $category
     */
    private function createCategoryPatterns(Category $category)
    {
        // retrieve meta data patterns
        $metaDataPatterns = $this->settingsRepository->getProperty(self::METADATA_STORAGE_KEY);

        // get locale
        $locale = app()->getLocale();

        $this->titlePattern = $metaDataPatterns[$locale]['title'];
        $this->descriptionPattern = $metaDataPatterns[$locale]['description'];
        $this->keywordsPattern = $metaDataPatterns[$locale]['keywords'];

        $this->replacementPatterns = [
            '/CATEGORY_NAME/',
        ];

        $this->replacementValues = [
            $category->name,
        ];
    }
}
