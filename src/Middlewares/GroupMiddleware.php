<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use Junges\ACL\Exceptions\Unauthorized;

class GroupMiddleware
{
    /**
     * Handle an incoming request
     *
     * @param $request
     * @param Closure $next
     * @param $groups
     * @return mixed
     */
    public function handle($request, Closure $next, $groups)
    {
        if (Auth::guest())
            throw Unauthorized::notLoggedIn();
        $groups = is_array($groups)
            ? $groups
            : explode('|', $groups);
        if (Auth::user()->hasAnyGroup($groups))
            return $next($request);
        throw Unauthorized::forGroups();
    }
}