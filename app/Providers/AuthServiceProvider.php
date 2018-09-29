<?php

namespace App\Providers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Policies\Admin\AttributePolicy;
use App\Policies\Admin\CategoryPolicy;
use App\Policies\Admin\ProductPolicy;
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
        Product::class => ProductPolicy::class,
        Attribute::class => AttributePolicy::class,
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
