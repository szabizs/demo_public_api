<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequestUpdatePermissionRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use stdClass;

class RolesResourceController extends Controller
{
	public function index()
	{
		return Inertia::render('Admin/Roles', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'roles' =>
                Role::query()
                    ->orderBy('name','ASC')
                    ->filter(\Illuminate\Support\Facades\Request::only('search', 'trashed'))
                    ->paginate(5)
                    ->withQueryString()
                    ->through(fn ($role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                    ]),
        ]);
	}

	public function create()
	{
		return Inertia::render('Admin/NewRole',[
            'permissions' => Permission::query()->select(['name','id'])->get(),
        ]);
	}

	public function store(StoreRoleRequest $request)
	{
        $role = Role::create([
            'name' => $request->name,
        ]);

        if($request->has('permissions') && count($request->get('permissions')) > 0) {
            $role->syncPermissions($request->get('permissions'));
        }

        return Redirect::route('admin.roles.index')->with('success', 'Role has been created.');
	}

	public function show(Role $role)
	{

        $usersWithRoles = $this->getUsersWithRoles();
        $roleWithUsers = $this->getRolesWithUsers($role);

		return Inertia::render('Admin/Role', [
            'role' => $roleWithUsers,
            'users' => $usersWithRoles,
            'rolePermissions' => $role->getAllPermissions()->pluck('id'),
            'allPermissions' => Permission::query()->select(['name','id'])->get(),
        ]);
	}

	public function edit($id)
	{
		//
	}

	public function update(UpdateRoleRequest $request, Role $role)
	{
        $role->update(['name' => $request->get('name')]);
		$role->users()->sync($request->get('users'));
        $role->syncPermissions($request->get('permissions'));
        return Redirect::route('admin.roles.show', $role)->with('success', 'Role has been successfully updated.');
	}

	public function destroy(Role $role)
	{
        $role->delete();
        return Redirect::route('admin.roles.index')->with('success', 'Role has been successfully deleted.');
	}

    private function getUsersWithRoles()
    {
        $collection = collect();
        User::query()->select(['name','email','id'])->with(['roles' => function($permission) {
            $permission->select(['id']);
        }])->get()->map(function($user) use(&$collection) {
            $data = new stdClass();
            $data->id = $user->id;
            $data->name = $user->name;
            $data->roles = collect();

            if($user->roles->count() > 0) {
                foreach($user->roles as $permission) {
                    $data->roles->push($permission->id);
                }
            }

            $collection->push($data);
        });

        return $collection->sortBy('name')->values();
    }

    private function getRolesWithUsers(Role $role)
    {
        $alteredRole = new stdClass();
        $alteredRole->id = $role->id;
        $alteredRole->name = $role->name;
        $alteredRole->users = collect();

        $role->load('users');

        if($role->users->count() > 0) {
            foreach($role->users as $user) {
                $alteredRole->users->push($user->id);
            }
        }

        return $alteredRole;
    }
}
