<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Junges\ACL\Exceptions\UnauthorizedException;

class HierarchicalPermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $permissions
     * @return bool
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permissions) ? $permissions : explode('|', $permissions);

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            $ability = '';
            foreach ($parts as $part) {
                $ability .= $ability ? '.'.$part : $part;

                if (Auth::user()->can($ability)) {
                    // Grant access on the first match
                    return $next($request);
                }
            }
        }
        throw UnauthorizedException::forPermissions();
    }
}
