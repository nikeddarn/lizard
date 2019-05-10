<?php /** @noinspection ALL */

namespace App\Http\Requests\Admin\Product;

use App\Models\Attribute;
use App\Rules\LeafCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CreateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name_ru' => ['required', 'string', 'max:64', Rule::unique('products')],
            'name_uk' => ['required', 'string', 'max:64', Rule::unique('products')],
            'url' => ['required', 'string', 'max:128', Rule::unique('products')],
            'model_ru' => ['nullable', 'string', 'max:64', Rule::unique('products')],
            'model_uk' => ['nullable', 'string', 'max:64', Rule::unique('products')],
            'articul' => 'nullable|string',
            'code' => 'nullable|string',
            'categories_id.*' => ['required', 'numeric', 'distinct', new LeafCategory()],
            'title_ru' => ['nullable', 'string', 'max:128', Rule::unique('products')],
            'title_uk' => ['nullable', 'string', 'max:128', Rule::unique('products')],
            'description_ru' => ['nullable', 'string', 'max:255', Rule::unique('products')],
            'description_uk' => ['nullable', 'string', 'max:255', Rule::unique('products')],
            'keywords_ru' => 'nullable|string|max:128',
            'keywords_uk' => 'nullable|string|max:128',
            'content_ru' => 'nullable|string',
            'content_uk' => 'nullable|string',
            'min_order_quantity' => 'nullable|integer',
            'price1' => 'nullable|numeric',
            'price2' => 'nullable|numeric',
            'price3' => 'nullable|numeric',
            'image.*' => 'nullable|image',
            'attribute_id.*' => 'nullable|integer',
            'attribute_values_id.*' => 'nullable|integer|distinct',
            'filter_id.*' => 'nullable|integer',
            'is_new' => 'integer',
            'warranty' => 'nullable|integer',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'volume' => 'nullable|numeric',
            'specification' => 'nullable|file',
            'video_youtube' => 'nullable|string|max:255',
            'video_mp4' => 'nullable|file',
            'video_webm' => 'nullable|file',
            'product_file_name_ru' => ['nullable', Rule::requiredIf(function (){
                return request()->get('product_file_name_uk') || request()->has('product_file');
            })],
            'product_file_name_uk' => ['nullable', Rule::requiredIf(function (){
                return request()->get('product_file_name_ru') || request()->has('product_file');
            })],
            'product_file' => ['nullable', 'file', Rule::requiredIf(function (){
                return request()->get('product_file_name_ru') || request()->get('product_file_name_uk');
            })],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            //check attributes
            if (request()->has('attribute_id')) {
                foreach (array_count_values(request()->get('attribute_id')) as $attributeId => $attributeArrayIndex) {

                    // allow any first attribute value
                    if ($attributeArrayIndex === 1) {
                        continue;
                    }

                    $attribute = Attribute::where('id', $attributeId)->first();

                    // disallow second attribute value if attribute disallow multiply values for same product
                    if (!$attribute->multiply_product_values) {
                        $validator->errors()->add('attribute_id', trans('validation.multiply_product_values', ['attribute' => $attribute->name]));
                    }
                }
            }

            // check video
            if ((request()->get('video_youtube') && request()->has('video_mp4')) || (request()->get('video_youtube') && request()->has('video_webm'))) {
                $validator->errors()->add('video_youtube', trans('validation.multiply_product_video_source'));
            }
        });
    }
}
