<?php

/**
 * Define and set locale.
 * Redirect to preferred user locale or change and store new user locale.
 */

namespace App\Http\Middleware\Locale;

use App\Contracts\Shop\UrlParametersInterface;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserLocale
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

        // get default locale
        $currentLocale = $this->getCurrentLocale($request, $canonicalLocale);

        // abort if wrong locale received
        if (!in_array($currentLocale, config('app.available_locales'))) {
            return abort(404);
        }

        // redirect to user's preferred locale
        if (config('shop.redirect_user_to_preferred_locale')) {

            // define user locale
            $userLocale = $this->defineUserLocale($request);

            // redirect if referrer is external
            if ($userLocale && $userLocale !== $currentLocale && !$this->isRefererSelfHost()) {
                return $this->createRedirect($request, $userLocale, $canonicalLocale);
            }
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
        $routeLocale = $request->route()->parameter('locale');

        return $routeLocale ? $routeLocale : $canonicalLocale;
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

        if ($request->session()->has(self::URL_PARAMETER_NAME)) {
            // get locale from session
            $locale = $request->session()->get(self::URL_PARAMETER_NAME);
        } elseif ($request->hasCookie(self::URL_PARAMETER_NAME)) {
            // get locale from cookie
            $locale = $request->cookie(self::URL_PARAMETER_NAME);
        } elseif (auth('web')->check()) {
            // get locale from user's preferences
            $locale = auth('web')->user()->locale;
        }

        return in_array($locale, config('app.available_locales')) ? $locale : null;
    }

    /**
     * Create redirect to preferred user locale.
     *
     * @param Request $request
     * @param string $locale
     * @param string $canonicalLocale
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function createRedirect(Request $request, string $locale, string $canonicalLocale)
    {
        $routeParameters = $request->route()->parameters();

        if ($locale === $canonicalLocale) {
            unset($routeParameters['locale']);
        } else {
            $routeParameters['locale'] = $locale;
        }

        return redirect(route($request->route()->getName(), $routeParameters));
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
        $request->session()->put(self::URL_PARAMETER_NAME, $locale);

        // set locale to cookie
        $request->cookie(self::URL_PARAMETER_NAME, $locale);
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
