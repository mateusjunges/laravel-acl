<?php

namespace Junges\ACL\Macros;

use Illuminate\Routing\Route;
use Illuminate\Support\Arr;

/**
 * @mixin Route
 */
class WithPermission
{
    public function __invoke(): callable
    {
        return function ($permissions = []) {
            $permissions = implode('|', Arr::wrap($permissions));

            $this->middleware("permission:$permissions");

            return $this;
        };
    }
}
