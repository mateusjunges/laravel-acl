<?php

namespace Junges\ACL\Macros;

use Illuminate\Routing\Route;
use Illuminate\Support\Arr;

/**
 * @mixin Route
 */
class WithGroup
{
    public function __invoke(): callable
    {
        return function ($groups = []) {
            $groups = implode('|', Arr::wrap($groups));

            $this->middleware("groups:$groups");

            return $this;
        };
    }
}
