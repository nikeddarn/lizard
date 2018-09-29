<?php

/**
 * Administrative policies.
 * Return array of roles that allowed to work with appropriate model.
 */

use App\Contracts\Auth\RoleInterface;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;

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
];