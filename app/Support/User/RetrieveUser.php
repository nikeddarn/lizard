<?php
/**
 * Get authenticated user from guard or retrieve temporary user by cookie
 */

namespace App\Support\User;


use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

trait RetrieveUser
{
    /**
     * @var string
     */
    private $cookieName = 'remember_token';


    /**
     * Retrieve or create user.
     *
     * @return User|\Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Model
     */
    protected function getOrCreateUser()
    {
        if (auth('web')->check()) {
            $user = auth('web')->user();
        } else {
            if (request()->hasCookie($this->cookieName)) {
                $user = User::query()->where('remember_token', (request()->cookie($this->cookieName)))->first();

                if (!$user) {
                    $user = $this->createUser();
                }
            } else {
                $user = $this->createUser();
            }
        }

        return $user;
    }

    /**
     * Retrieve or create user.
     *
     * @return User|\Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Database\Eloquent\Model|null
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

    /**
     * Create new user identifying by cookie.
     *
     * @return User
     */
    private function createUser(): User
    {
        $uuid = Str::uuid();

        $user = new User();
        $user->remember_token = $uuid;
        $user->save();

        // store user's cookie
        Cookie::queue(Cookie::forever('remember_token', $uuid));

        return $user;
    }
}
