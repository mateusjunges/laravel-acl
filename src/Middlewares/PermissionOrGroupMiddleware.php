<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        $permissions = is_array($groupOrPermissions)
            ? $groupOrPermissions
            : explode('|', $groupOrPermissions);
        if (! Auth::user()->hasAnyPermission($permissions)
            && ! Auth::user()->hasAnyGroup($permissions)) {
            throw UnauthorizedException::forGroupsOrPermissions();
        }

        return $next($request);
    }
}
