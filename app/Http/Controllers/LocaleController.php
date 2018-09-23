<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    /**
     * Change locale. Store in session and cookie.
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLocale(string $locale)
    {
        if(!in_array($locale, config('app.available_locales'))){
            return back();
        }

        // change locale in user model
        if (auth('web')->check()) {
            $user = auth('web')->user();

            $user->locale = $locale;
            $user->save();
        }

        // store locale in session
        session()->push('locale', $locale);

        // return back. store locale in cookie
        return back()->withCookie('locale', $locale);
    }
}
