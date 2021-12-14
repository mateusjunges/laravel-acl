<?php

namespace Junges\ACL\Middlewares;

use Closure;
use Junges\ACL\Exceptions\UnauthorizedException;

class GroupMiddleware
{
    public function handle($request, Closure $next, $groups, string $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $groups = is_array($groups)
            ? $groups
            : explode('|', $groups);

        if (! $authGuard->user()->hasAnyGroup($groups)) {
            throw UnauthorizedException::forGroups($groups);
        }

      return $next($request);
    }
}
