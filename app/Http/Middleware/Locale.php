<?php

/**
 * Set location for view templates by user locale or default.
 */

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
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
        // define user locale
        $locale = $this->defineUserLocale($request);

        // redirect to new locale

        // set locale
        $this->setLocale($locale);

        // store locale
        $this->storeLocale($request, $locale);

        // set locale's views folder
        View::addLocation(resource_path('views/' . $locale));


        return $next($request);
    }

    /**
     * Define user locale.
     *
     * @param Request $request
     * @return string
     */
    private function defineUserLocale(Request $request): string
    {
        if ($request->session()->has('locale')) {
            // get locale from session
            $locale = $request->session()->get('locale');
        } elseif ($request->hasCookie('locale')) {
            // get locale from cookie
            $locale = $request->cookie('locale');
        } elseif (auth('web')->check()) {
            // get locale from user's preferences
            $locale = auth('web')->user()->locale;
        } else {
            // default locale
            $locale = app()->getLocale();
        }

        // check locale availability
        if (!in_array($locale, config('app.available_locales'))) {
            $locale = app()->getLocale();
        }

        return $locale;
    }

    /**
     * Set and store locale.
     *
     * @param string $locale
     */
    private function setLocale(string $locale)
    {
        // set locale for applications
        app()->setLocale($locale);

        // set locale for string functions
        setlocale(LC_ALL, $locale);

        // set locale for carbon
        Carbon::setLocale($locale);
    }

    /**
     * Store locale.
     *
     * @param Request $request
     * @param string $locale
     */
    private function storeLocale(Request $request, string $locale)
    {
        if (auth('web')->check()) {
            // set locale to user's preferences
            auth('web')->user()->update([
                'locale' => $locale,
            ]);
        } else if (!$request->session()->has('locale')) {
            // set locale to session
            $request->session()->push('locale', $locale);
        } elseif (!$request->hasCookie('locale')) {
            // set locale to cookie
            $request->cookie('locale', $locale);
        }
    }
}
