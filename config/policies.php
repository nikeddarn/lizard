<?php

/**
 * Administrative policies.
 * Return array of roles that allowed to work with appropriate model.
 */

use App\Contracts\Auth\RoleInterface;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Product;
use App\Models\User;

return [

  Category::class  => [
      RoleInterface::ADMIN,
      RoleInterface::CONTENT_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    Product::class  => [
      RoleInterface::ADMIN,
      RoleInterface::CONTENT_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    Attribute::class  => [
      RoleInterface::ADMIN,
      RoleInterface::CONTENT_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    Brand::class  => [
      RoleInterface::ADMIN,
      RoleInterface::CONTENT_MANAGER,
      RoleInterface::USER_MANAGER,
  ],

    Filter::class  => [
      RoleInterface::ADMIN,
      RoleInterface::CONTENT_MANAGER,
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
