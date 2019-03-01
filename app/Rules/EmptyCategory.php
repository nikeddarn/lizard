<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class EmptyCategory implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // no parent category
        if (!$value){
            return true;
        }

        // no products and linked vendor categories
        return (bool)Category::query()
            ->where('id', $value)
            ->doesntHave('products')
            ->doesntHave('vendorCategories')
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.category_not_empty');
    }
}
