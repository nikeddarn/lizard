<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FavouriteProduct;
use App\Models\RecentProduct;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdminLoginForm()
    {
        return view('content.auth.admin.login.index');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function authenticated(Request $request, $user)
    {
        // set intended for redirect to admin
        if ($user->isEmployee()) {
            return redirect(route('admin.overview'));
        }else{
            return back();
        }
    }

    /**
     * The user has logged out of the application.
     *
     * @return mixed
     */
    protected function loggedOut()
    {
        return back();
    }
}
