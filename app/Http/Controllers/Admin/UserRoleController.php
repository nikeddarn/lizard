<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserRoleController extends Controller
{
    /**
     * @var UserRole
     */
    private $userRole;
    /**
     * @var Role
     */
    private $role;
    /**
     * @var User
     */
    private $user;

    /**
     * UserRoleController constructor.
     * @param UserRole $userRole
     * @param Role $role
     * @param User $user
     */
    public function __construct(UserRole $userRole, Role $role, User $user)
    {
        $this->userRole = $userRole;
        $this->role = $role;
        $this->user = $user;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(string $id)
    {
        $this->authorize('create', $this->userRole);

        $locale = app()->getLocale();

        $user = $this->user->newQuery()->findOrFail($id);

        $roles = $this->role->newQuery()->orderBy("title_$locale")->get();

        return view('content.admin.users.roles.create.index')->with([
            'user' => $user,
            'roles' => $roles,
        ]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->userRole);

        $user = $this->user->newQuery()->findOrFail($request->get('users_id'));

        // prevent add role to self
        if (auth('web')->id() === $user->id){
            return redirect(route('admin.users.show', $user->id));
        }

        $this->validate($request, [
            'roles_id' => ['integer', Rule::unique('user_role', 'roles_id')->where('users_id', $user->id)],
        ]);


        $user->roles()->attach($request->get('roles_id'));

        return redirect(route('admin.users.show', ['id' => $user->id]));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $userRole = $this->userRole->newQuery()->where([
            'users_id' => $request->get('users_id'),
            'roles_id' => $request->get('roles_id'),
        ])->firstOrFail();

        $this->authorize('delete', $userRole);

        $userRole->user->roles()->detach($userRole->roles_id);

        return redirect(route('admin.users.show', $userRole->users_id));
    }
}
