<?php

namespace App\Http\Middleware;

use App\Http\Services\CheckPermissionService;
use Closure;
use Illuminate\Http\Response;

class CheckPermission {


    /**
     * @param $request
     * @param  Closure  $next
     * @param ...$guards
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next, ...$guards) {

    	$user = auth()->user();
        $hasPermission = false;
        $routeName = strtolower($request->route()->getName());

    	if(!is_null($user)) {

            (new CheckPermissionService(name: $routeName));

    		if($user->hasRole('Super Admin')) {
    			$hasPermission = true;
			}

            if($user->can($request->route()->getName())) {
                $hasPermission = true;
            }

			if ($hasPermission) {
				return $next($request);
			}

            abort(Response::HTTP_FORBIDDEN, "You don't have access to the requested page. (" . $request->route()->getName() . ")");
		}

        return redirect()->to('/login')->with('message', 'Session has expired, please log in.');
    }
}
