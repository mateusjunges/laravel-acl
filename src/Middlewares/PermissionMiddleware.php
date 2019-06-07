<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        $permissions = is_array($permissions)
            ? $permissions
            : explode('|', $permissions);
        foreach ($permissions as $permission) {
            if (Auth::user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions();
    }
}
