<?php

/**
 * Define and set locale.
 */

namespace App\Http\Middleware\Locale;

use App\Contracts\Shop\UrlParametersInterface;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\View;

class AdminLocale
{
    /**
     * @var string
     */
    const URL_PARAMETER_NAME = UrlParametersInterface::LOCALE;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // get canonical locale
        $canonicalLocale = config('app.locale');

        // set new locale
        $this->setLocale($canonicalLocale);

        // add current and fallback locale's views folders
        $this->addViewLocations($canonicalLocale);

        return $next($request);
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
     * Add current and fallback locale's views folders.
     *
     * @param string $currentLocale
     */
    private function addViewLocations(string $currentLocale)
    {
        //get fallback locale
        $fallbackLocale = config('app.fallback_locale');

        // add current locale's views folder
        View::addLocation(resource_path('views/' . $currentLocale));

        // add fallback locale's views folder
        View::addLocation(resource_path('views/' . $fallbackLocale));
    }
}
