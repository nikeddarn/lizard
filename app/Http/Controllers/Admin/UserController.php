<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
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
    public function customers()
    {
        $this->authorize('view', $this->user);

        return view('content.admin.users.list.customers.index')->with([
            'users' => $this->user->newQuery()
                ->doesntHave('roles')
                ->orderBy('name')
                ->paginate(config('admin.show_items_per_page')),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function administrators()
    {
        $this->authorize('view', $this->user);

        $users = $this->user->newQuery()
            ->has('roles')
            ->orderBy('name')
            ->with('userRoles.role')->paginate(config('admin.show_items_per_page'));

        return view('content.admin.users.list.administrators.index')->with([
            'users' => $users,
        ]);
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
        $this->authorize('view', $this->user);

        $loggedUser = auth('web')->user();
        $user = $this->user->newQuery()->with('roles')->findOrFail($id);

        if ($user->hasAnyRole()) {
            $view = view('content.admin.users.show.administrators.index')->with('userRoles');
        } else {
            $view = view('content.admin.users.show.customers.index');
        }

        return $view->with([
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(string $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);

        $this->authorize('update', $user);

        return view('content.admin.users.update.customers.index')->with([
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(string $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);

        $this->authorize('update', $user);

        $user->price_group = request()->get('price_group');

        $user->save();

        return redirect(route('admin.users.show', ['id' => $user->id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $id)
    {
        $user = $this->user->newQuery()->findOrFail($id);

        $this->authorize('delete', $user);

        $user->delete();

        return back();
    }
}
