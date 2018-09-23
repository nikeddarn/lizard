<?php
/**
 * Composer for all common and user view
 */

namespace App\Http\Composers;


use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommonUserDataComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        if (auth('web')->check()) {
            $view->with($this->getUserData());
        }
    }

    private function getUserData()
    {
        $user = auth('web')->user();

        $userData = [];

        $userData['userName'] = $user->name;

        if ($user->avatar){
            $userData['userAvatar'] = Storage::disk('public')->url($user->avatar);
        }else{
            $userData['userAvatar'] = '/images/common/default_user_avatar.png';
        }

        return $userData;
    }
}