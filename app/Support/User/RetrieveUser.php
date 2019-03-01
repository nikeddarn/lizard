<?php
/**
 * Get authenticated user from guard or retrieve temporary user by cookie
 */

namespace App\Support\User;


use App\Models\User;

trait RetrieveUser
{
    /**
     * @var string
     */
    private $cookieName = 'remember_token';


    /**
     * Retrieve user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    protected function getUser()
    {
        if (auth('web')->check()) {
            return auth('web')->user();
        } else if (request()->hasCookie($this->cookieName)) {
            return User::query()->where('remember_token', (request()->cookie($this->cookieName)))->first();
        } else {
            return null;
        }
    }
}
