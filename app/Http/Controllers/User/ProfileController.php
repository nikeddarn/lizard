<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
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
    public function show():View
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
    public function showProfileForm()
    {
        return view('content.user.profile.edit.index')->with([
            'userProfile' => auth('web')->user(),
        ]);
    }

    /**
     * Check and store received user profile data.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        $this->validate($request, $this->rules($request->user()));

        $this->storeProfileData($request);

        return redirect(route('user.profile.show'));
    }

    /**
     * @param Request $request
     * @internal param array $data
     * @internal param User $user
     */
    private function storeProfileData(Request $request)
    {
        $userData = [];

        $nameParts = explode(' ', $request->get('name'));
        array_walk($nameParts, function (&$namePart) {
            $namePart = ucfirst($namePart);
        });
        $userData['name'] = implode(' ', $nameParts);

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->avatar->store('images/avatars', 'public');
        }

        $request->user()->update(array_merge($userData, $request->only(['email', 'phone'])));
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
            'avatar' => 'nullable|image|max:' . min(intval(ini_get('upload_max_filesize')) * 1024, config('user.avatar_max_filesize') * 1024),
        ];
    }
}
