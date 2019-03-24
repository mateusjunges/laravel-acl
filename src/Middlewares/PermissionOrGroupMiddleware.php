<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Junges\ACL\Exceptions\Unauthorized;

class PermissionOrGroupMiddleware
{
    /**
     * Handle an incoming request
     *
     * @param $request
     * @param Closure $next
     * @param $groupOrPermissions
     * @return mixed
     */
    public function handle($request, Closure $next, $groupOrPermissions)
    {
        if (Auth::guest())
            throw Unauthorized::notLoggedIn();
        $permissions = is_array($groupOrPermissions)
            ? $groupOrPermissions
            : explode('|', $groupOrPermissions);
        if (!Auth::user()->hasAnyPermission($permissions)
            && !Auth::user()->hasAnyGroup($permissions))
            throw Unauthorized::forGroupsOrPermissions();
        return $next($request);
    }
}