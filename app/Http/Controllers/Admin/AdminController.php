<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', $this->user);

        $users = $this->user->newQuery()
            ->has('roles')
            ->orderBy('name')
            ->with('roles')
            ->paginate(config('admin.show_items_per_page'));

        return view('content.admin.users.admins.list.index')->with(compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $id)
    {
        $user = $this->user->newQuery()->with('userRoles.role')->findOrFail($id);

        $this->authorize('view', $user);

        return view('content.admin.users.admins.show.index')->with(compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);

        $this->authorize('modify', $user);

        if ($user->balance == 0) {
            $user->delete();
            return redirect(route('admin.users.administrators'));
        } else {
            return back()->withErrors([trans('validation.balance_not_zero')]);
        }
    }
}
