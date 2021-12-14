<?php

namespace Junges\ACL\Middlewares;

use Closure;
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
        if (auth()->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $denied_groups = [];

        $groups = is_array($groups)
            ? $groups
            : explode('|', $groups);

        foreach ($groups as $group) {
            if (auth()->user()->hasGroup($group)) {
                return $next($request);
            }

            array_push($denied_groups, $group);
        }

        throw UnauthorizedException::forGroups($denied_groups);
    }
}
