<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use stdClass;

class PermissionsResourceController extends Controller
{
	public function index()
	{
		return Inertia::render('Admin/Permissions', [
            'filters' => \Illuminate\Support\Facades\Request::all('search', 'trashed'),
            'permissions' =>
                Permission::query()
                    ->orderBy('name','ASC')
                    ->filter(\Illuminate\Support\Facades\Request::only('search', 'trashed'))
                    ->paginate(5)
                    ->withQueryString()
                    ->through(fn ($permission) => [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'deleted_at' => $permission->deleted_at
                    ]),
        ]);
	}

	public function create()
	{
		return Inertia::render('Admin/NewPermission');
	}

	public function store(StorePermissionRequest $request)
	{
        Permission::create([
            'name' => strtolower($request->name),
        ]);

        return Redirect::route('admin.permissions.index')->with('success', 'Permission has been created.');
	}

	public function show(Permission $permission)
	{

        $usersWithPermissions = $this->getUsersWithPermissions();
        $permissionWithUsers = $this->getPermissionWithUsers($permission);

		return Inertia::render('Admin/Permission', [
            'permission' => $permissionWithUsers,
            'users' => $usersWithPermissions,
        ]);
	}

	public function edit($id)
	{
		//
	}

	public function update(UpdatePermissionRequest $request, Permission $permission)
	{
        $permission->update(['name' => strtolower($request->get('name'))]);
		$permission->users()->sync($request->get('users'));
        return Redirect::route('admin.permissions.show', $permission)->with('success', 'Permission has been successfully updated.');
	}

	public function destroy(Permission $permission)
	{
        $permission->delete();
        return Redirect::route('admin.permissions.index')->with('success', 'Permission has been successfully deleted.');
	}

    private function getUsersWithPermissions()
    {
        $collection = collect();
        User::query()->select(['name','email','id'])->with(['permissions' => function($permission) {
            $permission->select(['id']);
        }])->get()->map(function($user) use(&$collection) {
            $data = new stdClass();
            $data->id = $user->id;
            $data->name = $user->name;
            $data->permissions = collect();

            if($user->permissions->count() > 0) {
                foreach($user->permissions as $permission) {
                    $data->permissions->push($permission->id);
                }
            }

            $collection->push($data);
        });

        return $collection->sortBy('name')->values();
    }

    private function getPermissionWithUsers(Permission $permission)
    {
        $alteredPermission = new stdClass();
        $alteredPermission->id = $permission->id;
        $alteredPermission->name = $permission->name;
        $alteredPermission->users = collect();

        $permission->load('users');

        if($permission->users->count() > 0) {
            foreach($permission->users as $user) {
                $alteredPermission->users->push($user->id);
            }
        }

        return $alteredPermission;
    }
}
