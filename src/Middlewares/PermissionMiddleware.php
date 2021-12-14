<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Junges\ACL\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $permissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (auth()->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $denied_permissions = [];

        $permissions = is_array($permissions)
            ? $permissions
            : explode('|', $permissions);
        foreach ($permissions as $permission) {
            if (auth()->user()->can($permission)) {
                return $next($request);
            }

            array_push($denied_permissions, $permission);
        }

        throw UnauthorizedException::forPermissions($denied_permissions);
    }
}
