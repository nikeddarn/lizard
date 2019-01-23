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
     */
    protected function authenticated(Request $request, $user)
    {
        // associate created user with unregistered user by it's 'uuid' cookie
        if ($request->hasCookie('uuid')) {

            $uuid = $request->cookie('uuid');

            $this->favouriteProduct->newQuery()->where('uuid', $uuid)->update([
                'users_id' => $user->id,
            ]);

            $this->recentProduct->newQuery()->where('uuid', $uuid)->update([
                'users_id' => $user->id,
            ]);
        }

        // set intended for redirect to admin
        if ($user->isEmployee()) {
            session(['url.intended' => route('admin')]);
        }
    }
}
