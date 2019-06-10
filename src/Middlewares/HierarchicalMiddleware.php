<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Junges\ACL\Exceptions\UnauthorizedException;

class HierarchicalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $permission
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (Auth::guest()){
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);

        $permissions->map(function ($permission) use ($next, $request) {
           $partials = explode('.', $permission);
           $ability = '';
           foreach ($partials as $partial){
               //Recreate the ability with each partial:
               $ability .= $ability ? '.' . $partial : $partial;
               if (Auth::user()->can($ability)){
                   return $next($request);
               }
           }
           return $permission;
        });
        throw UnauthorizedException::forPermissions();
    }
}
