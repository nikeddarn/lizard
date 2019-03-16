<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     */
    public function index()
    {
        if (Gate::denies('users-edit')) {
            abort(401);
        }

        // only authenticate able users
        $users = $this->user->newQuery()
            ->whereNotNull('email')
            ->doesntHave('roles')
            ->orderBy('name')
            ->paginate(config('admin.show_items_per_page'));

        return view('content.admin.users.customers.list.index')->with(compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        if (Gate::denies('users-edit')) {
            abort(401);
        }

        $user = $this->user->newQuery()->findOrFail($id);

        return view('content.admin.users.customers.show.index')->with(compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        if (Gate::denies('users-edit')) {
            abort(401);
        }

        $user = $this->user->newQuery()->findOrFail($id);

        if ($user->balance == 0) {
            $user->delete();
            return redirect(route('admin.users.customers'));
        } else {
            return back()->withErrors([trans('validation.balance_not_zero')]);
        }


    }

    /**
     * Increase price group for user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function increasePriceGroup(Request $request)
    {
        if (Gate::denies('users-edit', auth('web')->user())) {
            abort(401);
        }

        $userId = $request->get('user_id');

        $user = $this->user->newQuery()->findOrFail($userId);

        $user->price_group = min($user->price_group + 1, 3);
        $user->save();

        if (request()->ajax()) {
            return $user->price_group;
        } else {
            return back();
        }
    }

    /**
     * Decrease price group for user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function decreasePriceGroup(Request $request)
    {
        if (Gate::denies('users-edit', auth('web')->user())) {
            abort(401);
        }

        $userId = $request->get('user_id');

        $user = $this->user->newQuery()->findOrFail($userId);

        $user->price_group = max($user->price_group - 1, 1);
        $user->save();

        if (request()->ajax()) {
            return $user->price_group;
        } else {
            return back();
        }
    }
}
