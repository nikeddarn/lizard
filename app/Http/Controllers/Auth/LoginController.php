<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FavouriteProduct;
use App\Models\RecentProduct;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * @var FavouriteProduct
     */
    private $favouriteProduct;
    /**
     * @var RecentProduct
     */
    private $recentProduct;

    /**
     * Create a new controller instance.
     *
     * @param FavouriteProduct $favouriteProduct
     * @param RecentProduct $recentProduct
     */
    public function __construct(FavouriteProduct $favouriteProduct, RecentProduct $recentProduct)
    {
        $this->middleware('guest')->except('logout');
        $this->favouriteProduct = $favouriteProduct;
        $this->recentProduct = $recentProduct;
    }

    public function showLoginForm()
    {
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        return view('content.auth.user.login.index');
    }

    /**
     * Show admin login form.
     *
     * @return View
     */
    public function showAdminLoginForm()
    {
        return view('content.auth.admin.login.index');
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param mixed $user
     * @return RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended($this->redirectTo);
    }

    /**
     * The user has logged out of the application.
     *
     * @return mixed
     */
    protected function loggedOut()
    {
        // redirect to main page if previous url was one of user's page
        if (stristr(url()->previous(), 'user')) {
            $locale = request()->get('locale');

            return redirect(route('main', ['locale' => $locale]));
        } else {
            return back();
        }
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     *
     */
    protected function validateLogin(Request $request)
    {
        // store auth type in session
        session()->put('auth_method', 'login');

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
