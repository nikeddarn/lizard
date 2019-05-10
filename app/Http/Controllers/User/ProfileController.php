<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\User;
use App\Support\ImageHandlers\UserImageHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{

    /**
     * Show user profile form with user profile data.
     *
     * @param StaticPage $staticPage
     * @return View
     */
    public function edit(StaticPage $staticPage)
    {
        $pageData = $staticPage->newQuery()->where('route', 'user.profile.edit')->first();

        $locale = app()->getLocale();

        $pageTitle = $pageData->{'title_' . $locale};
        $noindexPage = true;

        $userProfile = auth('web')->user();

        return view('content.user.profile.edit.index')->with(compact('userProfile', 'pageTitle', 'noindexPage'));
    }

    /**
     * Check and store received user profile data.
     *
     * @param Request $request
     * @param UserImageHandler $imageHandler
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, UserImageHandler $imageHandler)
    {
        $locale = $request->get('locale');

        $this->validate($request, $this->rules($request->user()));

        // create user data
        $userData = $this->createProfileData($request, $imageHandler);

        // update user
        $request->user()->update($userData);

        return redirect(route('user.profile.edit', ['locale' => $locale]));
    }

    /**
     * @param Request $request
     * @param UserImageHandler $imageHandler
     * @internal param array $data
     * @internal param User $user
     * @return array
     */
    private function createProfileData(Request $request, UserImageHandler $imageHandler):array
    {
        $userData = $request->only(['email', 'phone']);

        // upper case each part of name
        $nameParts = explode(' ', $request->get('name'));
        array_walk($nameParts, function (&$namePart) {
            $namePart = Str::ucfirst($namePart);
        });

        // collect name
        $userData['name'] = implode(' ', $nameParts);

        // create avatar
        if ($request->has('avatar')) {
            // source image path
            $avatarSourcePath = $request->avatar;

            // destination image path
            $avatarDestinationPath = 'images/users/avatars/' . uniqid() . '.jpg';

            $userData['avatar'] = $avatarDestinationPath;

            // create and store image
            $imageHandler->createUserAvatar($avatarSourcePath, $avatarDestinationPath);
        }

        return $userData;
    }

    /**
     * User profile validation rules.
     *
     * @param User $user
     * @return array
     */
    private function rules(User $user)
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id, 'id')],
            'phone' => ['nullable', 'regex:/^[\s\+\(\)\-0-9]{10,24}$/'],
            'avatar' => 'nullable|image|max:' . min(intval(ini_get('upload_max_filesize')) * 1024, config('images.user.avatar_max_filesize') * 1024),
        ];
    }
}
