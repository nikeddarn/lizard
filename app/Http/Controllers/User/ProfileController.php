<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ImageHandlers\UserImageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show user profile.
     *
     * @return View
     */
    public function index():View
    {
        return view('content.user.profile.show.index')->with([
            'userProfile' => auth('web')->user(),
        ]);
    }

    /**
     * Show user profile form with user profile data.
     *
     * @return View
     */
    public function edit()
    {
        return view('content.user.profile.edit.index')->with([
            'userProfile' => auth('web')->user(),
        ]);
    }

    /**
     * Check and store received user profile data.
     *
     * @param Request $request
     * @param UserImageHandler $imageHandler
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, UserImageHandler $imageHandler)
    {
        $this->validate($request, $this->rules($request->user()));

        // create user data
        $userData = $this->createProfileData($request, $imageHandler);

        // update user
        $request->user()->update($userData);

        return redirect(route('user.profile.show'));
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
