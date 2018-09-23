<?php

/**
 * Set location for view templates by user locale or default.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // define locale
        $locale = app()->getLocale();

        if (auth('web')->check()) {
            $user = auth('web')->user();
            if ($user->locale){
                $locale = $user->locale;
            }
        } else if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
        } elseif ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
        }

        if(!in_array($locale, config('app.available_locales'))){
            $locale = app()->getLocale();
        }

        // set locale's views folder
        View::addLocation(resource_path() . '/views/' . $locale);

        // set app locale
        app()->setLocale($locale);

        return $next($request);
    }
}
