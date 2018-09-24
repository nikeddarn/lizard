<?php
/**
 * Redirect to admin login if user is unauthenticated.
 * Check the user has admin roles.
 */

namespace App\Http\Middleware;


use App\Contracts\Auth\RoleInterface;
use Closure;
use Illuminate\Http\Request;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth('web')->check()) {
            return redirect()->guest(route('admin.login'));
        }

        $adminRoles = [
            RoleInterface::ADMIN,
            RoleInterface::USER_MANAGER,
            RoleInterface::VENDOR_MANAGER,
            RoleInterface::STOREKEEPER,
            RoleInterface::SERVICEMAN,
        ];

        // user has one of admin's role
        if (auth('web')->user()->roles()->whereIn('id', $adminRoles)->count()) {
            return $next($request);
        }

        return abort(403, 'Forbidden');
    }
}