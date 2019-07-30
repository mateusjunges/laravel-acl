<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Junges\ACL\Exceptions\UnauthorizedException;

class GroupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $groups
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $groups)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        $groups = is_array($groups)
            ? $groups
            : explode('|', $groups);

        foreach ($groups as $group) {
            if (Auth::user()->hasGroup($group)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forGroups();
    }
}
