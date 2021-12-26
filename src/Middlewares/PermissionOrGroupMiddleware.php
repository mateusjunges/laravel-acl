<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Junges\ACL\Exceptions\UnauthorizedException;

class PermissionOrGroupMiddleware
{
    public function handle($request, Closure $next, $groupOrPermissions, string $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($groupOrPermissions)
            ? $groupOrPermissions
            : explode('|', $groupOrPermissions);

        if (! $authGuard->user()->hasAnyGroup($permissions) && ! $authGuard->user()->hasAnyPermission($permissions)) {
            throw UnauthorizedException::forGroupsOrPermissions($permissions);
        }

        return $next($request);
    }
}
