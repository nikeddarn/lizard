<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => ':attribute и подтверждение не совпадает.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'Поле :attribute имеет повторяющееся значение.',
    'email'                => 'Атрибут :attribute должен быть действительным адресом электронной почты.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'Атрибут :attribute должен быть файлом.',
    'filled'               => 'Поле :attribute должно иметь значение.',
    'gt'                   => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file'    => 'The :attribute must be greater than :value kilobytes.',
        'string'  => 'The :attribute must be greater than :value characters.',
        'array'   => 'The :attribute must have more than :value items.',
    ],
    'gte'                  => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file'    => 'The :attribute must be greater than or equal :value kilobytes.',
        'string'  => 'The :attribute must be greater than or equal :value characters.',
        'array'   => 'The :attribute must have :value items or more.',
    ],
    'image'                => 'Атрибут :attribute должен быть изображением.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => ':attribute должен быть целым числом.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'lt'                   => [
        'numeric' => 'The :attribute must be less than :value.',
        'file'    => 'The :attribute must be less than :value kilobytes.',
        'string'  => 'The :attribute must be less than :value characters.',
        'array'   => 'The :attribute must have less than :value items.',
    ],
    'lte'                  => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file'    => 'The :attribute must be less than or equal :value kilobytes.',
        'string'  => 'The :attribute must be less than or equal :value characters.',
        'array'   => 'The :attribute must not have more than :value items.',
    ],
    'max'                  => [
        'numeric' => 'Атрибут :attribute не может быть больше, чем :max.',
        'file'    => 'Атрибут :attribute не может быть больше, чем :max килобайт.',
        'string'  => 'Атрибут :attribute не может быть длиннее, чем :max символов.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'Атрибут :attribute должен быть файлом типа: :values.',
    'mimetypes'            => 'Атрибут :attribute должен быть файлом типа: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => ':attribute должен содержать не менее :min символов.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => 'Атрибут :attribute должен быть числом.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'Поле :attribute обязательно для заполнения.',
    'required_if'          => 'Поле :attribute является обязательным, когда :other соответствует :value.',
    'required_unless'      => 'Поле :attribute является обязательным, если :other не соответствует :values.',
    'required_with'        => 'Поле :attribute является обязательным при наличии :values.',
    'required_with_all'    => 'Поле :attribute является обязательным при наличии :values.',
    'required_without'     => 'Поле :attribute является обязательным при отсутствии :values.',
    'required_without_all' => 'Поле :attribute является обязательным при отсутствии :values.',
    'same'                 => ':attribute и :other должны совпадать.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => ':attribute должен быть строкой.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'Такое значение :attribute уже занято.',
    'uploaded'             => 'Не удалось загрузить :attribute.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

    // auth
    'old_password_confirmed' => 'Неверный старый пароль.',

    // category
    'no_categories' => 'Нет ни одной категории. Сначала создайте любую категорию.',
    'leaf_category' => 'Выбранная категория должна быть листом дерева категорий',
    'category_not_empty' => 'Родительская категория содержит товары или связана с продавцом',

    //product
    'multiply_product_values' => 'Атрибут :attribute не допускает использование нескольких значений для продукта.',
    'product_in_stock' => 'Товар в наличии или зарезервирован на складе',
    'product_has_not_price' => 'Товар не имеет цены',
    'multiply_product_video_source' => 'Источником видео может быть ссылка на YouTube или видеофайл в одном или двух форматах.',

    // user
    'balance_not_zero' => 'Невозможно удалить пользователя. Баланс пользователя отличен от ноля.',

];
