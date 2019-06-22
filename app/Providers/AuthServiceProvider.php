<?php

namespace App\Providers;

use App\Contracts\Auth\RoleInterface;
use App\Models\Order;
use App\Models\User;
use App\Policies\Admin\UserPolicy;
use App\Policies\Shop\OrderPolicy;
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
        User::class => UserPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // show and edit vendor catalog
        Gate::define('vendor-catalog', function ($user) {
            return $user->hasAnyRole([
                RoleInterface::ADMIN,
                RoleInterface::CONTENT_MANAGER,
            ]);
        });

        // show local catalog
        Gate::define('local-catalog-show', function ($user) {
            return $user->hasAnyRole([
                RoleInterface::ADMIN,
                RoleInterface::CONTENT_MANAGER,
                RoleInterface::USER_MANAGER,
            ]);
        });

        // show and edit local catalog
        Gate::define('local-catalog-edit', function ($user) {
            return $user->hasAnyRole([
                RoleInterface::ADMIN,
                RoleInterface::CONTENT_MANAGER,
            ]);
        });

        // show and edit users (not admins)
        Gate::define('users-edit', function ($user) {
            return $user->hasAnyRole([
                RoleInterface::ADMIN,
                RoleInterface::USER_MANAGER,
            ]);
        });

        // show and edit admins
        Gate::define('admins-edit', function ($user) {
            return $user->hasAnyRole([
                RoleInterface::ADMIN,
            ]);
        });

        // show and edit settings
        Gate::define('settings-edit', function ($user) {
            return $user->hasAnyRole([
                RoleInterface::ADMIN,
                RoleInterface::CONTENT_MANAGER,
            ]);
        });

        // page content
        Gate::define('content-edit', function ($user) {
            return $user->hasAnyRole([
                RoleInterface::ADMIN,
                RoleInterface::CONTENT_MANAGER,
            ]);
        });
    }
}
