<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Junges\ACL\Exceptions\UnauthorizedException;

class PermissionOrGroupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $groupOrPermissions
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $groupOrPermissions)
    {
        if (auth()->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($groupOrPermissions)
            ? $groupOrPermissions
            : explode('|', $groupOrPermissions);

        if (! $this->hasAnyPermission(auth()->user(), $permissions)
            && ! $this->hasAnyGroup(auth()->user(), $permissions)) {
            throw UnauthorizedException::forGroupsOrPermissions();
        }

        return $next($request);
    }

    /**
     * @param $user
     * @param array $permissions
     * @return bool
     */
    private function hasAnyPermission($user, array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $user
     * @param array $groups
     * @return bool
     */
    private function hasAnyGroup($user, array $groups)
    {
        foreach ($groups as $group) {
            if ($user->hasGroup($group)) {
                return true;
            }
        }

        return false;
    }
}
