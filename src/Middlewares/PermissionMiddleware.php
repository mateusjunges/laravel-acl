<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Junges\ACL\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permissions, string $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $denied_permissions = [];

        $permissions = is_array($permissions)
            ? $permissions
            : explode('|', $permissions);

        foreach ($permissions as $permission) {
            if ($authGuard->user()->can($permission)) {
                return $next($request);
            }

            $denied_permissions[] = $permission;
        }

        throw UnauthorizedException::forPermissions($denied_permissions);
    }
}
