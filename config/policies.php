<?php

/**
 * Administrative policies.
 * Return array of roles that allowed to work with appropriate model.
 */

use App\Contracts\Auth\RoleInterface;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Product;
use App\Models\User;

return [

  Category::class  => [
      RoleInterface::ADMIN,
      RoleInterface::VENDOR_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    Product::class  => [
      RoleInterface::ADMIN,
      RoleInterface::VENDOR_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    Attribute::class  => [
      RoleInterface::ADMIN,
      RoleInterface::VENDOR_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    Filter::class  => [
      RoleInterface::ADMIN,
      RoleInterface::VENDOR_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    User::class => [

        'admin' => [
            RoleInterface::ADMIN,
        ],

        'customer' => [
            RoleInterface::ADMIN,
            RoleInterface::USER_MANAGER,
        ],
    ],
];