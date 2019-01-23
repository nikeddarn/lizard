<?php

namespace App\Http\Controllers\Auth;

use App\Models\FavouriteProduct;
use App\Models\RecentProduct;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
        $this->favouriteProduct = $favouriteProduct;
        $this->recentProduct = $recentProduct;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * The user has been registered.
     *
     * @param Request $request
     * @param  User $user
     * @return void
     */
    protected function registered(Request $request, $user)
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
    }
}
