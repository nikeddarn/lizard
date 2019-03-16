<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\User\CreateUserRole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $user = $this->user->newQuery()->withCount('roles')->findOrFail($id);

        $this->authorize('modify', $user);

        $roles = $this->role->newQuery()->get();

        return view('content.admin.users.admins.role.index')->with(compact('user', 'roles'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRole $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CreateUserRole $request)
    {
        $userId = $request->get('users_id');
        $roleId = $request->get('roles_id');

        $user = $this->user->newQuery()->findOrFail($userId);

        $this->authorize('modify', $user);

        $user->roles()->attach($roleId);

        // set wholesale price group for employees
        $user->price_group = 3;
        $user->save();

        return redirect(route('admin.users.administrators.show', ['id' => $user->id]));
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
        $userId = $request->get('users_id');
        $roleId = $request->get('roles_id');

        $user = $this->user->newQuery()->withCount('roles')->findOrFail($userId);

        $this->authorize('modify', $user);

        $user->roles()->detach($roleId);

        if ($user->roles_count > 1){
            // user is still admin
            return redirect(route('admin.users.administrators.show', $userId));
        }else{
            // user is customer after deleted single role
            return redirect(route('admin.users.customers.show', $userId));
        }
    }
}
