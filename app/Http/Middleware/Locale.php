<?php

/**
 * Define user locale and redirect to ut if needing.
 * Create canonical url if needing.
 * Create possible locales links data.
 */

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class Locale
{
    /**
     * @var string
     */
    const LOCALE_PARAMETER_NAME = 'locale';

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

        // get default locale
        $currentLocale = $this->getCurrentLocale($request, $canonicalLocale);

        // abort if wrong locale received
        if (!in_array($currentLocale, config('app.available_locales'))) {
            return abort(404);
        }

        // define user locale
        $userLocale = $this->defineUserLocale($request);

        // locale was changed by user
        if ($userLocale && $userLocale !== $currentLocale && !$this->isRefererSelfHost()) {
            // redirect to user's preferred locale
            return redirect($this->createPageUrlForLocale($userLocale, $canonicalLocale));
        }

        // create change locale urls for view
        view()->share('availableLocalesLinksData', $this->createAvailableLocalesLinksData($currentLocale, $canonicalLocale));

        // add canonical url to any view's header
        if ($currentLocale !== $canonicalLocale) {
            view()->share('canonicalUrl', $this->createCanonicalUrl());
        }

        // set new locale
        $this->setLocale($currentLocale);

        // store new locale
        $this->storeLocale($request, $currentLocale);

        // add current and fallback locale's views folders
        $this->addViewLocations($currentLocale);

        return $next($request);
    }

    /**
     * Get current locale.
     *
     * @param $request
     * @param string $canonicalLocale
     * @return string
     */
    private function getCurrentLocale($request, string $canonicalLocale): string
    {
        if ($request->has(self::LOCALE_PARAMETER_NAME)) {
            return $request->get(self::LOCALE_PARAMETER_NAME);
        } else {
            return $canonicalLocale;
        }
    }

    /**
     * Define user locale.
     *
     * @param Request $request
     * @return string|null
     */
    private function defineUserLocale(Request $request)
    {
        $locale = null;

        if ($request->session()->has(self::LOCALE_PARAMETER_NAME)) {
            // get locale from session
            $locale = $request->session()->get(self::LOCALE_PARAMETER_NAME);
        } elseif ($request->hasCookie(self::LOCALE_PARAMETER_NAME)) {
            // get locale from cookie
            $locale = $request->cookie(self::LOCALE_PARAMETER_NAME);
        } elseif (auth('web')->check()) {
            // get locale from user's preferences
            $locale = auth('web')->user()->locale;
        }

        return in_array($locale, config('app.available_locales')) ? $locale : null;
    }

    /**
     * Is request from this host ?
     *
     * @return bool
     */
    private function isRefererSelfHost()
    {
        return parse_url(config('app.url'), PHP_URL_HOST) === parse_url(request()->server('HTTP_REFERER'), PHP_URL_HOST);
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
        }

        // set locale to session
        $request->session()->push(self::LOCALE_PARAMETER_NAME, $locale);

        // set locale to cookie
        $request->cookie(self::LOCALE_PARAMETER_NAME, $locale);
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

    /**
     * Create url for locale using canonical locale.
     *
     * @param string $locale
     * @param string $canonicalLocale
     * @return string
     */
    private function createPageUrlForLocale(string $locale, string $canonicalLocale): string
    {
        // create page's url for locale
        if ($locale === $canonicalLocale) {
            $localeUrl = $this->createCanonicalUrl();
        } else {
            $localeUrl = $this->createNotCanonicalUrl($locale);
        }

        return $localeUrl;
    }

    /**
     * Create canonical locale url.
     *
     * @return string
     */
    private function createCanonicalUrl(): string
    {
        // remove 'lang' from query string
        $queryStringParameters = array_diff_key(request()->query(), array_flip([self::LOCALE_PARAMETER_NAME]));

        // create url to current route without 'lang' parameter
        return url()->current() . (empty($queryStringParameters) ? '' : '?' . http_build_query($queryStringParameters));
    }

    /**
     * Create not canonical locale url.
     *
     * @param string $locale
     * @return string
     */
    private function createNotCanonicalUrl(string $locale): string
    {
        // add 'lang' to query string
        $queryStringParameters = array_merge(request()->query(), [self::LOCALE_PARAMETER_NAME => $locale]);

        // create url to current route with 'lang' parameter
        return url()->current() . (empty($queryStringParameters) ? '' : '?' . http_build_query($queryStringParameters));
    }

    /**
     * Create possible locales links data.
     *
     * @param string $currentLocale
     * @param string $canonicalLocale
     * @return array
     */
    private function createAvailableLocalesLinksData(string $currentLocale, string $canonicalLocale)
    {
        $possibleLocalesLinksData = [];

        $possibleLocales = config('app.available_locales');

        if (count($possibleLocales) > 1) {

            foreach ($possibleLocales as $locale) {

                // create page url
                $pageUrl = $this->createPageUrlForLocale($locale, $canonicalLocale);

                // collect locale data
                $possibleLocalesLinksData[Str::upper($locale)] = [
                    'url' => $pageUrl,
                    'class' => $locale === $currentLocale ? 'disabled' : '',
                ];
            }
        }

        return $possibleLocalesLinksData;
    }
}
