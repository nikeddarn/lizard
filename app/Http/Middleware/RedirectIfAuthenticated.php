<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $locale = app()->getLocale();
            $routeLocale = $locale === config('app.canonical_locale') ? '' : $locale;

            return redirect(route('main', ['locale' => $routeLocale]));
        }

        return $next($request);
    }
}
