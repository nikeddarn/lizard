<?php

namespace App\Providers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\CategoryFilter;
use App\Models\Filter;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductFilter;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\UserRole;
use App\Policies\Admin\AttributePolicy;
use App\Policies\Admin\AttributeValuePolicy;
use App\Policies\Admin\CategoryFilterPolicy;
use App\Policies\Admin\CategoryPolicy;
use App\Policies\Admin\FilterPolicy;
use App\Policies\Admin\ProductAttributePolicy;
use App\Policies\Admin\ProductFilterPolicy;
use App\Policies\Admin\ProductImagePolicy;
use App\Policies\Admin\ProductPolicy;
use App\Policies\Admin\UserPolicy;
use App\Policies\Admin\UserRolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

        Category::class => CategoryPolicy::class,
        CategoryFilter::class => CategoryFilterPolicy::class,

        Product::class => ProductPolicy::class,
        ProductImage::class => ProductImagePolicy::class,
        ProductAttribute::class => ProductAttributePolicy::class,
        ProductFilter::class => ProductFilterPolicy::class,

        Attribute::class => AttributePolicy::class,
        AttributeValue::class => AttributeValuePolicy::class,

        Filter::class => FilterPolicy::class,

        User::class => UserPolicy::class,
        UserRole::class => UserRolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
