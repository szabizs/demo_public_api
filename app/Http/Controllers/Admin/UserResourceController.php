<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class UserResourceController extends Controller
{
	public function index()
	{
        $users = User::filter(\Illuminate\Support\Facades\Request::only('search', 'trashed'))->orderByName()->get(['id', 'name', 'email','deleted_at']);
        return Inertia::render('Admin/Users', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'users' => $users
        ]);
	}

	public function store(StoreUserRequest $request)
	{
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return Redirect::route('admin.users.index')->with('success', 'User has been created.');
	}

    public function create() {
        return Inertia::render('Admin/NewUser');
    }

	public function show($id)
	{
        $user = User::find($id);
		return Inertia::render('Admin/User', [
            'user' => $user,
            'userRoles' => $user->getAllRoles()->pluck('id'),
            'userPermissions' => $user->getAllPermissions()->pluck('id'),
            'allRoles' => Role::query()->select(['name','id'])->get(),
            'allPermissions' => Permission::query()->select(['name','id'])->get(),
        ]);
	}

    public function edit($id)
	{
        $user = User::find($id);
		return Inertia::render('Admin/User', [
            'user' => $user,
            'userRoles' => $user->getAllRoles()->pluck('id'),
            'userPermissions' => $user->getAllPermissions()->pluck('id'),
            'allRoles' => Role::query()->select(['name','id'])->get(),
            'allPermissions' => Permission::query()->select(['name','id'])->get(),
        ]);
	}

	public function update(UpdateUserRequest $request, User $user)
	{
		$hasPassword = !is_null($request->get('password'));

        if($hasPassword) {
            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'))
            ]);
        }

        if(!$hasPassword) {
            $user->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
            ]);
        }

        $user->syncRoles($request->get('roles'));
        $user->syncPermissions($request->get('permissions'));

        return Redirect::route('admin.users.show', $user)->with('success', 'User has been successfully updated.');
	}

	public function destroy(User $user)
	{
        $user->delete();
        return Redirect::route('admin.users.index')->with('success', 'User has been successfully deleted.');
	}
}
