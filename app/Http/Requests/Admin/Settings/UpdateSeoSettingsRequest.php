<?php
/**
 * Update SEO settings request.
 */

namespace App\Http\Requests\Admin\Settings;


use Illuminate\Foundation\Http\FormRequest;

class UpdateSeoSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameRule = 'string|max:256';
        $titleRule = 'string|max:256';
        $descriptionRule = 'string|max:512';
        $keywordsRule = 'string';

        return [
            'category_title_ru' => $titleRule,
            'category_title_uk' => $titleRule,
            'category_description_ru' => $descriptionRule,
            'category_description_uk' => $descriptionRule,
            'category_keywords_ru' => $keywordsRule,
            'category_keywords_uk' => $keywordsRule,

            'virtual_category_name_ru' => $nameRule,
            'virtual_category_name_uk' => $nameRule,
            'virtual_category_title_ru' => $titleRule,
            'virtual_category_title_uk' => $titleRule,
            'virtual_category_description_ru' => $descriptionRule,
            'virtual_category_description_uk' => $descriptionRule,
            'virtual_category_keywords_ru' => $keywordsRule,
            'virtual_category_keywords_uk' => $keywordsRule,

            'filtered_category_name_ru' => $nameRule,
            'filtered_category_name_uk' => $nameRule,
            'filtered_category_title_ru' => $titleRule,
            'filtered_category_title_uk' => $titleRule,
            'filtered_category_description_ru' => $descriptionRule,
            'filtered_category_description_uk' => $descriptionRule,
            'filtered_category_keywords_ru' => $keywordsRule,
            'filtered_category_keywords_uk' => $keywordsRule,

            'product_title_ru' => $titleRule,
            'product_title_uk' => $titleRule,
            'product_description_ru' => $descriptionRule,
            'product_description_uk' => $descriptionRule,
            'product_keywords_ru' => $keywordsRule,
            'product_keywords_uk' => $keywordsRule,
        ];
    }
}
