<?php

namespace App\Http\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;

class CheckPermissionService
{
    /**
     * Create the permission if it does not exist.
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        Cache::rememberForever('permissions', function() {
            return [];
        });

        if(Cache::has('permissions')) {
            $permissions = Cache::get('permissions');

            /**
             * Permission exists in the cache, dont do another query.
             */
            if(!isset($permissions[$name])) {
                $permission = Permission::where('name',$name)->firstOrCreate([
                    'name' => $name
                ]);
                $permissions[] = $permission->name;
                Cache::put('permissions', $permissions);
            }
        }

    }
}
