<?php

namespace MateusJunges\ACL\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use MateusJunges\ACL\Exceptions\Unauthorized;

class GroupMiddleware
{
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